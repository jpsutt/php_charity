<section class="wrapper style2 special">
    <div class="inner narrow">
        <header>
            <h2>Send Resources</h2>
        </header>
        <form id="sendForm" class="grid-form" method="post" action="./Utility/process.php">
            <div class="form-control">
                <label for="remail">Recipient's Email</label>
                <input name="recipemail" id="remail" type="text" list="remaillist"><datalist id="remaillist"></datalist>
            </div>
            <div class="form-control narrow">
                <label for="resourcessent">Resources Sent</label>
                <input name="resourcessent" id="resourcessent" type="number" max="<?php echo($_SESSION['user']->resources); ?>" min="2" placeholder="2">
            </div>
            <div class="form-control narrow">
                <label for="resourcesreceived">Resources Received</label>
                <input name="resourcesreceived" id="resourcesreceived" type="number" min="1" placeholder="1" readonly>
                <input name="resourcesreceived" id="resourcesreceivedhidden" type="hidden" min="1" placeholder="1">
            </div>
            <ul class="actions">
                <li><input value="Send Resources" name="SendResources" type="submit"></li>
            </ul>
        </form>
    </div>
</section>