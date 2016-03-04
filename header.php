<style>
    #header {
        text-align: right;
        width: 100%;
    }
</style>

<script>
    $(document).ready(function () {
        $("#clear").click(function () {
            $.post("/Session_Manage/clear_session.php");
            location.replace("/index.php");
        });
        $("#return").click(function () {
            location.assign("/index.php");
        });
    });
</script>
<div id="header">
    <link rel="stylesheet" type="text/css" href="/style.css">
    <button id="return">Home</button>
    <button id="clear">Clear Profile and Restart</button>
    <hr>
</div>
