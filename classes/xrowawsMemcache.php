<?php
use Stash\Driver\Memcache;
use Stash\Item;
use Stash\Pool;

class xrowawsMemcache
{
    static function clearCache()
    {
        $dbINI = eZINI::instance( 'file.ini' );
        if($dbINI->hasVariable("eZDFSClusteringSettings", "DBName"))
        {
            $db_name = $dbINI->variable( "eZDFSClusteringSettings", "DBName" );
        }
    
        $db = eZDB::instance();
    
        $query = "USE ". $db_name;
    
        $db->query($query);
        $db->query("DELETE FROM ezdfsfile WHERE name_trunk like '%/cache/%'");
    
        $memINI = eZINI::instance( 'xrowaws.ini' );
        
        if($memINI->hasVariable("MemcacheSettings", "Host") AND $memINI->hasVariable("MemcacheSettings", "Port"))
        {
            $mem_host = $memINI->variable( "MemcacheSettings", "Host" );
            $mem_port = $memINI->variable( "MemcacheSettings", "Port" );
        }
        
        $driver = new Memcache(array('servers' => array($mem_host, $mem_port)));
        $pool = new Pool($driver);
        $pool->flush();
    }
}