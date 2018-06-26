<!-- Two -->

<script type="text/javascript">
    function submitform() {   document.search.submit(); }
</script>

<section id="two" class="wrapper style2 special">
    <div class="inner narrow">
        <header>
            <h2>Search Transactions</h2>
        </header>
        <form class="grid-form" method="post" action="#">
            <div class="4u 12u$(small)">
                <h3>Search By:</h3>
            </div>
            <div class="4u 12u$(small)">
                <input type="checkbox" id="useremail" name="useremail">
                <label for="useremail">User Email</label>
            </div>
            <div class="4u 12u$(small)">
                <input type="checkbox" id="daterange" name="daterange">
                <label for="daterange">Date Range</label>
            </div>
            <span style="padding: 0.5em"></span>
            <div class="form-control">
                <label for="email">User's Email</label>
                <input name="email" id="email" type="text">
            </div>
            <div class="form-control narrow">
                <label for="startdate">Start Date</label>
                <input name="startdate" id="startdate" value="date" type="date">
            </div>
            <div class="form-control narrow">
                <label for="enddate">End Date</label>
                <input name="enddate" id="enddate" value="date" type="date">
            </div>
            <ul class="actions">
                <li><a href="javascript: submitform()" class="button icon fa-search">Search</a></li>
            </ul>
        </form>
    </div>
</section>