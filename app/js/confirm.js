    function confirmdelete(url) {
        var agree=confirm("Esta seguro de eliminar dato(s)?");
        if (agree)
        location.replace(url);
    }
	
    function confirmdeletef() {
        var agree=confirm("Esta seguro de eliminar dato(s)?");
        if (agree)
        return true;
		else
		  return false;
    }	

    function confirmunpost(url) {
        var agree=confirm("Are you sure you wish to unpost this?");
        if (agree)
        location.replace(url);
    }

    function confirmemail(url) {
        var agree=confirm("OK to send an email?");
        if (agree)
        location.replace(url);
    }