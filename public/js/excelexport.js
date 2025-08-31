function ExportToExcel(item, type, fn, dl) {
    var elt = item[0];
    var wb = XLSX.utils.table_to_book(elt, {
        sheet: 'sheet1',
    });
    set_right_to_left(wb);
    return dl
        ? XLSX.write(wb, {
              bookType: type,
              bookSST: true,
              type: 'base64',
          })
        : XLSX.writeFile(
              wb,
              fn || `${$('.reports-header h3').first().text()}.${type || 'xlsx'}`
          );
}
