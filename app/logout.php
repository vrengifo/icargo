<?php
		session_start();
		session_unregister('sUsername');
        session_unregister('sTerminal');
        session_unregister('sOficina');
        session_unregister('sEstacion');
        
        $destino="location:index1.php?idTer=".$idTer."&ipTer=".$ipTer;
		header($destino);
?>
