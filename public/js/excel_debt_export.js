function ExportDebtAgesToExcel(item,type, fn, dl) {
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.table_to_sheet(item.find('#report_table')[0]);

    // Set custom style for cell A1 in the worksheet
    var cellA1 = "A1";
    if (!ws[cellA1]) ws[cellA1] = {};
    if (!ws[cellA1].s) ws[cellA1].s = {};
    ws[cellA1].s.fill = {
        fgColor: {
            rgb: "FFFF00"
        }
    };

    var userTreeTable = item.find('#user_tree_table')[0];
    if (userTreeTable) {
        var wsUserTree = XLSX.utils.table_to_sheet(userTreeTable);

        // Apply styling to the <thead> section of the user_tree_table
        var theadRange = XLSX.utils.decode_range(wsUserTree["!ref"]);
        var theadRows = [];
        for (var R = theadRange.s.r; R <= theadRange.e.r; ++R) {
            var row = XLSX.utils.encode_row(R);
            theadRows.push(row);
        }
        var theadCells = theadRows.map(row => `A${row}`);
        theadCells.forEach(cell => {
            var cellProps = wsUserTree[cell];
            if (!cellProps.s) cellProps.s = {};
            cellProps.s.fill = {
                fgColor: {
                    rgb: "C0C0C0"
                } // Set background color for thead cells
            };
        });

        // Add empty rows between the two tables
        var emptyRows = Array(5).fill({}); // Create an array of empty objects for the empty rows
        var wsEmptyRows = XLSX.utils.sheet_add_json({}, emptyRows, {
            skipHeader: true
        });
        XLSX.utils.sheet_add_json(ws, XLSX.utils.sheet_to_json(wsEmptyRows), {
            origin: -1
        });

        XLSX.utils.sheet_add_json(ws, XLSX.utils.sheet_to_json(wsUserTree), {
            origin: -1
        });
    }

    set_right_to_left(wb);
    XLSX.utils.book_append_sheet(wb, ws, "كشف حساب");

    let excel_name = `اعمار ديون  - ***`;
    return dl ? XLSX.write(wb, {
            bookType: type,
            bookSST: true,
            type: 'base64'
        }) :
        XLSX.writeFile(wb, fn || (excel_name + '.' + (type || 'xlsx')));
}