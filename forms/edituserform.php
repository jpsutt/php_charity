<section id="two" class="wrapper style2 special">
    <div class="inner narrow">
        <header>
            <h2>Edit User Information</h2>
        </header>
        <form class="grid-form" method="post" action="#">
            <div class="form-control">
                <label for="editemail">Email</label>
                <input name="editemail" id="editemail" type="text" list="editemaillist"><datalist id="editemaillist"></datalist>
            </div>
            <div class="form-control">
                <label for="password">Password</label>
                <input name="password" id="editpassword" type="password">
            </div>
            <div class="form-control narrow">
                <label for="points">Points</label>
                <input name="points" id="editpoints" type="text" placeholder="0">
            </div>
            <div class="form-control narrow">
                <label for="resources">Resources</label>
                <input name="resources" id="editresources" type="text" placeholder="0">
            </div>
            <div class="form-control narrow">
                <label for="collectionlvl">Collection Level</label>
                <input name="collectionlvl" id="editcollectionlvl" type="number" min="1" placeholder="1">
            </div>
            <div class="form-control narrow">
                <input type="checkbox" id="editisactive" name="isactive">
                <label for="isactive">Active</label>
            </div>
            <?php

            if ($_SESSION['user']->adminlvl > 1) {
                echo <<<HERE
            <div class="form-control narrow adminpriv">
                <input type="checkbox" id="editisadmin" name="isadmin">
                <label for="isadmin">Admin</label>
            </div>
            <div class="form-control inner adminpriv" id="editadminlvl">
                <label for="adminlvl">Admin Level</label>
                <div class="select-wrapper">
                    <select name="adminlvl" id="category">
                        <option value=""></option>
                        <option value="1">Administrator</option>
                        <option value="2">Super Administrator</option>
                    </select>
                </div>
            </div>
HERE;
            }
            ?>
            <ul class="actions">
                <li><input value="Edit" type="submit" class="button"></li>
                <li><input value="Remove" type="submit" class="button special"></li>
            </ul>
        </form>
    </div>
</section>