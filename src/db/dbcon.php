<?php

namespace Vosiz\VaTools\Db;

use Vosiz\Utils\Collections\Dictionary;
use Vosiz\VaTools\Structure\Credentials;

require_once(__DIR__.'/exc.php');
require_once(__DIR__.'/query.php');


class DbConnectionConfig {

    private $Dsn;           public function GetDsn()        { return $this->Dns;        }
    private $Credentials;   public function GetCredentials(){ return $this->Credentials;}
    private $Atts;          public function GetAtts()       { return $this->Atts;       }

    /**
     * Constructor - connection string, login credentials
     */
    public function __construct(string $dsn, Credentials $creds) {

        $this->Dns = $dsn;
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

            throw new DbException("DB connection info creation failed: %s", $exc->getMessage());
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

            $this->pdo = new \PDO($cfg->GetDsn(), $cfg->GetCredentials()->GetUser(), $cfg->GetCredentials()->GetPassword());
            foreach($cfg->GetAtts()->ToArray() as $id => $att) {

                $this->pdo->setAttribute($id, $att);
            }

        } catch(\PDOException $exc) {

            if (preg_match('/\[(\d+)\]/', $exc->getMessage(), $m) && $m[1] == 1049) {

                throw new DbNotFoundException($cfg->GetDsn());
            }

            throw new DbException($exc->getMessage());

        } catch(\Exception $exc) {

            throw new DbException($exc->getMessage());
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
            
        } catch (\Exception $exc) {

            throw new DbException($exc->getMessage());
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
                throw new DbConnection("Connection check FALSE");

            return new DbConnectionInfo($this->pdo);

        } catch(\Exception $exc) {

            throw new DbException("Connection failed: %s", $exc->getMessage());
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

            throw new DbException("Connection check failed");
        }

    }
}
