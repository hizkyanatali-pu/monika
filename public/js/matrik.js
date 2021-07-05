var gridApp = angular.module('matrikApp', []);
gridApp.directive('ngRightClick', function($parse) {
  return function(scope, element, attrs) {
    var fn = $parse(attrs.ngRightClick);
    element.bind('contextmenu', function(event) {
      scope.$apply(function() {
        event.preventDefault();
        fn(scope, {
          $event: event
        });
      });
    });
  };
});
gridApp.factory('Excel',function($window){
    var uri='data:application/vnd.ms-excel;base64,',
        template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
        format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
    return {
        tableToExcel:function(tableId,worksheetName){
            var table=$(tableId),
                ctx={worksheet:worksheetName,table:table.html()},
                href=uri+base64(format(template,ctx));
            return href;
        }
    };
})
gridApp.controller('MatrikCtrl', function(Excel, $timeout, $scope, $document, $rootScope, $http) {
  var hideContextMenu = function() {
    $scope.isContextMenuVisible = false;
    if (!$rootScope.$$phase) {
      $rootScope.$apply();
    }
  };
  $scope.numRows = 0;
  $scope.numColumns = 0;
  $scope.export = false;

  $scope.isContextMenuVisible = false;
  $scope.contextmenuRowIndex = -1;
  $scope.contextmenuColumnIndex = -1
  $scope.openContextMenu = function($event, rowIndex, columnIndex) {
    $event.preventDefault();
    
    if ($event.button === 0) {
      $scope.isContextMenuVisible = false;
      return;
    }

    $scope.contextmenuRowIndex = rowIndex;
    $scope.contextmenuColumnIndex = columnIndex;
    $scope.contextMenuStyle = {
      top: $event.clientY + 'px',
      left: $event.clientX + 'px'
    };
    $scope.isContextMenuVisible = true;
  };
  $scope.addRow = function() {
    var i,
      record,
      cell,
      index = $scope.contextmenuRowIndex;

    record = [];
    for (i = 0; i < $scope.numColumns; i++) {
      cell = {
        value: ''
      }
      record.push(cell);
    }

    $scope.records.splice(index, 0, record);
    $scope.isContextMenuVisible = false;
    $scope.numRows = $scope.records.length;
  };
  $scope.removeRow = function() {
    var index = $scope.contextmenuRowIndex;
    $scope.records.splice(index, 1);
    $scope.isContextMenuVisible = false;
    $scope.numRows = $scope.records.length;
  };
  $scope.addColumn = function() {
    var i, record;
    for(i = 0; i < $scope.records.length; i++) {
      record = $scope.records[i];
      record.splice($scope.contextmenuColumnIndex, 0, {value: ''});
    }
    
    $scope.numColumns = record.length;
  };
  $scope.removeColumn = function() {
    var i, record;
    for(i = 0; i < $scope.records.length; i++) {
      record = $scope.records[i];
      record.splice($scope.contextmenuColumnIndex, 1);
    }
    
    $scope.numColumns = record.length;
  };

  $document.bind('click', function($evt) {
    var target = angular.element($evt.target).closest('table');
    if (target.length === 0) {
      hideContextMenu();
    }
  });
  $scope.initData = function(){
    $scope.numRows = 2;
    $scope.numColumns = 20;
    var pathArray = window.location.pathname.split('/');
    var postData = $.param({'rev':+pathArray[2],'top':+pathArray[3], [csrfName]: csrfHash});
    console.log('postData',postData);
    
    return $http.post('/matrik/getdata', postData, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}})
              // .success(function(data){
              //   console.log(data.data);
              //   var a =data.data.concat($scope.records);
              //   $scope.records = a; //console.log('$scope.records',a)

              // });
              .then(function (response){
                var  data = response.data
                
                var a =data.data.concat($scope.records);
                $scope.records = a; //console.log('$scope.records',a)
              },function (error){
                  alert('error loading data')
              });
  }
  $scope.init = function() {
   
    var i, j, column, cell,dval;
    var records = [],
      record;
    $scope.numRows = 2;
    $scope.numColumns = 20;
    for (i = 0; i < $scope.numRows; i++) {
      record = [];
      for (j = 0; j < $scope.numColumns; j++) {
       
        if( i < 1) { // buat dummy  
          var jval = j +1
          if(j < 9) dval = jval
          if(j  >= 9) dval = 1+ (jval)
        } else {dval=''}
        
        cell = {
          value: ''
        }
        record.push(cell);
      }
      records.push(record);
    }
    $scope.records = records;
    
  }
  $scope.init();
  $scope.initData()
 

  $scope.dataToSend = function(){
   
    if( $scope.records) {
      const dataFiltered =  $scope.records.filter(function(record){ 
          if(record[0].value !=='') return record
      })
     return dataFiltered;
    }
  }

  $scope.exportData = function () {
      $scope.export = true;

    $timeout(function(){
    var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{CouponDetails}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
            , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }

        var table = document.getElementById("table-matrik");
        var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML };
        var url = uri + base64(format(template, ctx));
        var a = document.createElement('a');
        a.href = url;
        a.download = 'Exportdata.xls';
        a.click();
        $timeout(function(){$scope.export = false;},2000);
    },2000);
   // $timeout(function(){$scope.export = false;},1000);
};

});