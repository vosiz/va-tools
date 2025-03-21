<?php

namespace Vosiz\VaTools\Db;

use Vosiz\Utils\Collections\Collection;
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
        $this->Atts = new Collection();
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

        } catch(Exception $exc) {

            throw new DbException($exc->getMessage());
        }
        

        //$this->pdo = new \PDO($dsn, $user, $password);
        //$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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
}
