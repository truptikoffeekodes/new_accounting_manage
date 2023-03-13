<?php 

namespace App\Controllers;

class Cron extends BaseController{
    

public function backup(){

    $db = \Config\Database::connect();
    $builder = $db->table('company');
    $builder->select('DataSource,name');
    $builder->where('is_delete',0);
    $result = $builder->get();
    $result_array = $result->getResultArray();

    $dbHost = 'localhost'; // Database Host
    $dbUser = getenv('database.default.username');
    $dbPass = getenv('database.default.password');
    
    $new_date = date('Y-m-d', strtotime('-7 days'));
    
    foreach($result_array as $row){
        $name = str_replace(' ', '', $row['name']);
       
        $dbFile = $name.date('Y-m-d').'.sql.gz';
        $delete = $name.$new_date.'.sql.gz';
        
        exec( 'mysqldump --host="'.$dbHost.'" --user="'.$dbUser.'" --password="'.$dbPass.'" "'.$row['DataSource'].'" | gzip > "'.getcwd() .'/../DBbackup/'. $dbFile.'"' );

        exec('rm -f /var/www/html/DBbackup/'.$delete);
    }
}

}

?>