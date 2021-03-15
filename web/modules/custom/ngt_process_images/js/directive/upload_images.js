myApp.directive('ngUploadImages', ['$http', '$rootScope',ngUploadImages]);

function ngUploadImages($http){
    
    var directive = {
        restrict: 'EA',
        controller: UploadImagesController,
        link: linkFunc
    };

    return directive;

    function linkFunc(scope, el, attr, ctrl){
        var config = drupalSettings.ngtBlock[scope.uuid_data_ng_node_right_course];
        
    }

}

UploadImagesController.$inject = ['$scope', '$http', '$rootScope','$interval', '$window'];
function UploadImagesController($scope, $http, $rootScope){
    
}