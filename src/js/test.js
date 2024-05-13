function doGet(e) {
    return querySheet(e);
  }
  
  function doPost(e) {
    return querySheet(e);
  }
  
  function querySheet(e) {
    // Check if required parameters are present
    var ss = SpreadsheetApp.openById(e.parameter.ID); // Corrected to e.parameter.ID
    var sh = ss.getSheetByName(e.parameter.SH);
    
    // Check if sheet with provided name exists
    if (!sh) {
      return ContentService.createTextOutput("Sheet not found");
    }
    
    var rg = sh.getName() + "!" + sh.getDataRange().getA1Notation();
    var sql = e.parameter.SQL;
    var qry = '=QUERY(' + rg + ', "' + sql + '", 1)';
    
    var ts = ss.insertSheet();
    var setQuery = ts.getRange(1, 1).setFormula(qry);
    SpreadsheetApp.flush();
    
    var getResult = ts.getDataRange().getValues();
    ss.deleteSheet(ts);
    
    var outString = '';
    for (var row = 0; row < getResult.length; row++) {
      outString += getResult[row].join(',') + '\n';
    }
    return ContentService.createTextOutput(outString);
  }