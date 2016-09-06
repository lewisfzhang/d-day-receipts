<?php
	error_reporting(E_ERROR | E_PARSE); //doesn't report small errors
    $db = new SQLite3('masterStudent16-17.sqlite3'); //connect
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sanitizer</title>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"> <!--W3.CSS stylesheet-->
    </head>
    <body>
        <form class="w3-container" method="post">
            <input type="submit" name="sanitize" value="Sanitize" class="w3-btn w3-theme">
        </form>
		<?php
            if(isset($_POST['sanitize'])){
				$statement = $db -> prepare('SELECT * FROM yearbookSales1516');
                $result = $statement->execute();

                //create an array for all of the column values
                $firstNameArray[] = [];
				$lastNameArray[] = [];
				$studentIdArray[] = [];

                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($firstNameArray, $row['FirstName']); //set the values in to the array
					array_push($lastNameArray, $row['LastName']); //set the values in to the array
                }
				$index = 0;
				foreach($firstNameArray as $firstName){
					$lastName = $lastNameArray[$index];
					//echo "$firstName $lastName <br>";
					unset($studentIdArray);
					$studentIdArray[] = [];
					$statement = $db -> prepare('SELECT BCPStudId FROM master WHERE StudFirstName = :firstName AND StudLastName = :lastName;'); 
					$statement -> bindValue(':firstName', $firstName);
					$statement -> bindValue(':lastName', $lastName);
					$result = $statement -> execute();
					while($row = $result->fetchArray(SQLITE3_ASSOC)){
						$studentIdArray = $row['BCPStudId']; //set the values in to the array
					}
					$statement = $db -> prepare('UPDATE yearbookSales1516 SET Student_ID_Number = :studentID WHERE FirstName = :firstName AND LastName = :lastName;');
					$currentStudentID = gettype($studentIdArray[0]);
					echo "$currentStudentID <br>";
					$statement -> bindValue(':studentID', 0);
					$statement -> bindValue(':firstName', $firstName);
					$statement -> bindValue(':lastName', $lastName);
					$statement -> execute();
					$index++; //increment index
				}
			}
		?>
    </body>
</html>
