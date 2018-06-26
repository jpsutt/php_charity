<style>
    #NavBar {
        position: fixed;
        list-style-type: none;
        margin: 0;
        padding: 0.05em 1em 0em 0em;
        overflow: hidden;
        z-index: 10;
    }

    #NavBar li {
        float: right;
    }

    #NavBar form, ul {
            margin: 0 auto;
            display: block;
            text-decoration: none;
        }
    }

</style>


<script type="text/javascript">
    $(document).ready(function() {
        //JS goes here.
    });
</script>

<ul id="NavBar" class="actions small fit">
   <form method="POST" action="Utility/logout.php">
       <li><input id="navlogout" class="button small fit" value="Logout" type="submit"></input></li>
   </form>
    <?php
        if ((isset($_SESSION['user']->adminlvl)) && ($_SESSION['user']->adminlvl > 0)){
        echo "
        <ul class='actions'>
            <li><input id='btnedituser' class='button small fit' value='Edit Users' type='submit'></input></li>
        </ul>";
    }
    ?>
    <form method="POST" action="index.php">
        <li><input id="navhome" class="button small fit" value="Home" type="submit"></input></li>
    </form>
</ul>