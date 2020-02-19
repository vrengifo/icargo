<?php
 include('includes/main.php');
 include('adodb/tohtml.inc.php');
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
Nombre: Vico <br>
<?php
  $nombre="Vico";
  $fecha=date("Y-m-d H:i:s");
  //$fecha=NULL;
?>
Fecha : <?=$fecha?> <br>
<?php
  $cadena_fecha="to_date('".$fecha."','YYYY-MM-DD HH24:MI:SS')";
  //$cadena_fecha="to_date('".$fecha."','YYYY-MM-DD')";
  $sqli="insert into prueba values ('$nombre',$cadena_fecha)";
  echo "sqli = $sqli <br>";
  $rs = &$conn->Execute($sqli);
  $sql="select nombre,TO_CHAR(fecha,'YYYY-MM-DD HH24:MI:SS') from prueba";
  $rs = &$conn->Execute($sql);
  while (!$rs->EOF)
  {
     $vnombre=$rs->fields[0];
     $vfecha=$rs->fields[1];
     echo "$vnombre --- $vfecha <br>";
     $rs->MoveNext();
  }
?>
</body>
</html>
