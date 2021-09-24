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

        $data = [TB_NAME];
        //query
        try
		{
            $stmnt = $dbh->prepare('SELECT * FROM main_tbl order by conn_date desc limit 50');

            echo '|============== START OF REPORT ==============|' . '</br>' . '</br>' . '</br>';
            
            if ($stmnt->execute($data)) 
			{
                if ($stmnt->rowCount($stmnt) > 0) 
				{
                    // Attempt select query execution
                    $result_q = $stmnt->fetch(PDO::FETCH_ASSOC);

                    echo "<table>";
                    echo "<tr>";
                    echo "<th>PC NAME   </th>";
                    echo "<th>START UP DATE (GMT +0)  </th>";
                    echo "<th>  USER NAME </th>";
                    echo "</tr>";

                    while ($result = $stmnt->fetch(PDO::FETCH_ASSOC)) 
					{
                        echo "<tr>";
                        echo "<td>" . $result['pc_id'] . "</td>";
                        echo "<td>" . $result['conn_date'] . "</td>";
                        echo "<td>" . $result['pc_dscrptn'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }

            echo '</br>';
            echo '|============== END OF REPORT ==============|' . '</br>';
        }
		catch (Exception $e) 
		{
            echo $e->getMessage();
            exit;
        }
        ?>
    </body>
</html>
