<?php

function connect()
	{
	$conn = oci_connect('ptr', 'ptr', 'localhost:1521/xe');
	if (!$conn)
		{
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES) , E_USER_ERROR);
		}
	  else return $conn;
	} //end connect

// /Used to check if a row with the specified value exist in a table
// /INPUT $attribute: index name in $_GET or $_POST
// /INPUT $table: name of table in database
// /INPUT $column: name of column to check against in database
// /INPUT $getOrpost: specifies where the data is stored, options:$_GET or $_POST

function CheckExist($attribute, $table, $column, $getOrpost)
	{
	if (isset($getOrpost))
		{
		if (!empty($getOrpost) && !empty($getOrpost[$attribute]))
			{
			$input = htmlspecialchars($getOrpost[$attribute]);
			$pdo = connect();
			$sql = 'SELECT COUNT(' . $column . ') FROM ' . $table . ' where ' . $column . ' = :attribute';
			$prepare = oci_parse($pdo, $sql);
			oci_bind_by_name($prepare, ':attribute', $input);
			if (oci_execute($prepare))
				{
				$res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
				if ($res['COUNT(' . $column . ')'] != 0)
					{
					return true;
					}
				  else
					{
					return false;
					}
				}
			  else
				{
				$e = oci_error($prepare);
				echo $e['message'];
				}
			}
		}
	}

function CheckExistExt($attribute, $table, $column, $wherecolumn)
	{
	if (isset($attribute))
		{
		if (!empty($attribute))
			{
			$input = htmlspecialchars($attribute);
			$pdo = connect();
			$sql = 'SELECT COUNT(' . $column . ') FROM ' . $table . ' where ' . $wherecolumn . ' = :attribute';
			$prepare = oci_parse($pdo, $sql);
			oci_bind_by_name($prepare, ':attribute', $input);
			if (oci_execute($prepare))
				{
				$res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
				if ($res['COUNT(' . $column . ')'] != 0)
					{
					return true;
					}
				  else
					{
					return false;
					}
				}
			  else
				{
				$e = oci_error($prepare);
				echo $e['message'];
				exit();
				}
			}
		}
	}

// /Used to return single cell from database
// /INPUT $table: table in the database where to look for the data
// /INPUT $column: the name of the column you want to select
// /INPUT $where_column: the name of the column that contains the data that needs to match the input
// /INPUT $where: the data that will be looked for in the specified column.

function GrabData($table, $column, $where_column, $where)
	{
	$input = $where;
	$pdo = connect();
	$sql = 'SELECT ' . $column . ' FROM ' . $table . ' where ' . $where_column . ' = :attribute';
	$prepare = oci_parse($pdo, $sql);
	oci_bind_by_name($prepare, ':attribute', $input);
	if (oci_execute($prepare))
		{
		$res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
		if ($res != null)
			{
			return $res;
			}
		  else
			{
			return false;
			}
		}
	  else
		{
		$e = oci_error($prepare);
		echo $e['message'];
		}
	}

// /Used to return the results of a specified mySQL query
// /$query is the basic mySQL query eg: "SELECT * FROM users WHERE email = :email AND password = :password".
// /$bind is a nested array, must be in pairs, eg: 'array(array(':email', 'generic@email.com'), array(':password', 'passwordtext'))'

function GrabMoreData($query, $bind = null)
	{
	$pdo = connect();
	$sql = $query;
	$prepare = oci_parse($pdo, $sql);
	if (!empty($bind))
		{
		foreach($bind as $attribute)
			{
			oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
			/*                                 echo $attribute[0]." ".$attribute[1];*/
			}
		}

	if (oci_execute($prepare))
		{
		$res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
		if ($res != null)
			{
			return $res;
			}
		  else
			{
			return false;
			}
		}
	  else
		{
		$e = oci_error($prepare);
		echo $e['message'];
		}
	}

function GrabAllData($query, $bind = null)
	{
	$pdo = connect();
	$sql = $query;
	$prepare = oci_parse($pdo, $sql);
	if (!empty($bind))
		{
		foreach($bind as $attribute)
			{
			oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
			}
		}

	if (oci_execute($prepare))
		{
		oci_fetch_all($prepare, $res);
		if ($res != null)
			{
			return $res;
			}
		  else
			{
			return false;
			}
		}
	  else
		{
		$e = oci_error($prepare);
		echo $e['message'];
		}
	}

function InsertData($query, $bind = null)
	{
	$pdo = connect();
	$sql = $query;
	$prepare = oci_parse($pdo, $sql);
    if(!empty($bind))
    {
        foreach($bind as $attribute)
            {
            oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
            }}
	if (oci_execute($prepare))
    {return 'success';}
    else{
		$e = oci_error($prepare);
		echo $e['message'];
		}
	}
?>