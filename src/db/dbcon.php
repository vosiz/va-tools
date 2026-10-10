<?php

namespace Vosiz\VaTools\Db;

use Vosiz\Utils\Collections\Dictionary;
use Vosiz\VaTools\Structure\Credentials;

require_once(__DIR__.'/exc.php');
require_once(__DIR__.'/query.php');


class DbConnectionConfig {

    private $Dsn;           public function GetDsn()        { return $this->Dsn;        }
    private $Credentials;   public function GetCredentials(){ return $this->Credentials;}
    private $Atts;          public function GetAtts()       { return $this->Atts;       }

    /**
     * Constructor - connection string, login credentials
     */
    public function __construct(string $dsn, Credentials $creds) {

        $this->Dsn = $dsn;
        $this->Credentials = $creds;
        $this->Atts = new Dictionary();
    }

    /**
     * Add attribute
     * @param int $att id of attribute
     * @param mixed|null $value value
     */
    public function AddAttr(int $att, $value = NULL) {

        $this->Atts->Add($value, $att, true);
    }
}


class DbConnectionInfo {

    private $Medium;
    private $User;
    private $CurrentUser;
    private $Database;
    private $Hostname;
    private $Port;

    /**
     * Constructor - fetches info
     * @param \PDO $pdo
     * @throws DbException
     */
    public function __construct(\PDO $pdo) {

        try {

            $stmt = $pdo->query("SELECT USER(), CURRENT_USER(), DATABASE(), @@hostname, @@port");
            $info_arr = $stmt->fetch(\PDO::FETCH_ASSOC);
            $addt_conn_info = $pdo->getAttribute(\PDO::ATTR_CONNECTION_STATUS);

            $this->Medium = $addt_conn_info;
            $this->User = $info_arr["USER()"];
            $this->CurrentUser = $info_arr["CURRENT_USER()"];
            $this->Database = $info_arr["DATABASE()"];
            $this->Hostname = $info_arr["@@hostname"];
            $this->Port = $info_arr["@@port"];

        } catch (\Exception $exc) {

            throw DbException::CreateInner($exc, "DB connection info creation failed: %s", $exc->getMessage());
        }
    }

    /**
     * ToString override
     */
    public function __toString() {

        return sprintf(
            "Connected as %s (%s) to %s:%s/%s (%s)",
            $this->User,
            $this->CurrentUser,
            $this->Hostname,
            $this->Port,
            $this->Database,
            $this->Medium
        );
    }

    /**
     * Returns info as string
     * @return string
     */
    public function AsString() {

        return $this->__toString();
    }

    /**
     * Returns info as array
     * @return array
     */
    public function AsArray() {

        $info = [];
        $info['User']           = $this->User;;
        $info['Current user']   = $this->CurrentUser;
        $info['Database']       = $this->Database;
        $info['Hostname']       = $this->Hostname;
        $info['Port']           = $this->Port;
        $info['Medium']         = $this->Medium;
        return $info;
    }
}


/**
 * Simple connection to database, PDO-based
 */
class DbConnection {

    protected $pdo;

    /**
     * Constructor - with connection config
     */
    public function __construct(DbConnectionConfig $cfg) {

        try {

            $this->pdo = new \PDO($cfg->GetDsn(), $cfg->GetCredentials()->GetUser(), $cfg->GetCredentials()->RevealPassword());
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            foreach($cfg->GetAtts()->ToArray() as $id => $att) {

                $this->pdo->setAttribute($id, $att);
            }

        } catch(\PDOException $exc) {

            if($this->IsDbMissing($exc)) {

                throw new DbNotFoundException($cfg->GetDsn());
            }

            throw DbException::CreateInner($exc, $exc->getMessage());

        } catch(\Exception $exc) {

            throw DbException::CreateInner($exc, $exc->getMessage());
        }
        
    }


    /**
     * Creates basic querier
     * @param string $table db table name
     * @throws Exception basic exception
     */
    public function Query(string $table) {

        try {

            return new QueryBuilder($this->pdo, $table);
            
        } catch (DbException $exc) {

            throw $exc;

        } catch (\Exception $exc) {

            throw DbException::CreateInner($exc, $exc->getMessage());
        }
        
    }

    /**
     * Returns last inserted ID
     * @return int
     */
    public function LastInsertId() {

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Executes raw SQL with optional parameters
     * @param string $sql
     * @param array $params
     * @return bool
     * @throws DbException
     */
    public function Raw(string $sql, array $params = []) {

        try {

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);

        } catch(\PDOException $exc) {

            throw DbException::CreateInner($exc, $exc->getMessage());
        }
    }

    /**
     * Connection info
     * @return array
     * @throws DbException
     */
    public function ConnInfo() {

        try {

            if(!$this->CheckConn())
                throw new DbException("Connection check FALSE");

            return new DbConnectionInfo($this->pdo);

        } catch(\Exception $exc) {

            throw DbException::CreateInner($exc, "Connection failed: %s", $exc->getMessage());
        }
        
    }

    
    /**
     * Checks if connected
     * @return bool (connected)
     * @throws DbException
     */
    private function CheckConn() {

        try {

            $this->pdo->query("SELECT 1");
            return true;

        } catch(\Exception $exc) {

            throw DbException::CreateInner($exc, "Connection check failed");
        }

    }

    /**
     * Checks if exception says the database does not exist
     * @param \PDOException $exc
     * @return bool
     */
    private function IsDbMissing(\PDOException $exc) {

        // driver error code, message parsing only when driver gives none
        if(isset($exc->errorInfo[1]))
            return $exc->errorInfo[1] == 1049;

        return preg_match('/\[(\d+)\]/', $exc->getMessage(), $m) && $m[1] == 1049;
    }
}
