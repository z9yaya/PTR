$("#search").keyup(function() {
    var value = this.value;

    $("table").find("tr").each(function(index) {
        if (!index) return;
        var id = $(this).find("td").first().text();
        $(this).toggle(id.indexOf(value) !== -1);
    });
});