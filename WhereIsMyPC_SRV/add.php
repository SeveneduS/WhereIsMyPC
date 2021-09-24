<!DOCTYPE html>
<!--
Eduard Ragimov
WhereIsMyPC - Computer's location tracking software.
WhereIsMyPC Server V 1.0.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once './conf/configuration.php';

        $secretKey = SECRETKEY;

        try 
		{
            $dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        } 
		catch (Exception $exc) 
		{
            echo '<h1>An error has ocurred.</h1><pre>', $exc->getMessage(), '</pre>';
            exit;
        }

        $hash = filter_input(INPUT_GET, 'hash');
        $pcguid = filter_input(INPUT_GET, 'pc_id');
        $client_ip = get_client_ip();
        $curent_date = date("Y-m-d H:i:s");
        $userid = filter_input(INPUT_GET, 'pc_dscr');

		$data = [filter_input(INPUT_GET, 'pc_id'),
			$client_ip,
			$curent_date,
			filter_input(INPUT_GET, 'pc_dscr')];

		try 
		{
			$ii_data = $dbh->prepare('INSERT into main_tbl VALUES (NULL, ?, ?, ?, ?)');

			if ($ii_data->execute($data)) 
			{
				echo 'DONE';
			} 
			else 
			{
				echo 'ERROR';
			}
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage();
			exit;
		}
		
        ?>
    </body>
</html>


