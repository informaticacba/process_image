var myApp = angular.module("ngtApp", ['ngSanitize','angularChart']).config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

myApp.config(
    function setupConfig( $httpProvider ) {
        // Wire up the traffic cop interceptors. This method will be invoked with
        // full dependency-injection functionality.
        // --
        // NOTE: This approach has been available since AngularJS 1.1.4.
        $httpProvider.interceptors.push( interceptHttp );
        // We're going to TRY to track the outgoing and incoming HTTP requests.
        // I stress "TRY" because in a perfect world, this would be very easy
        // with the promise-based interceptor chain; but, the world of
        // interceptors and data transformations is a cruel she-beast. Any
        // interceptor may completely change the outgoing config or the incoming
        // response. As such, there's a limit to the accuracy we can provide.
        // That said, it is very unlikely that this will break; but, even so, I
        // have some work-arounds for unfortunate edge-cases.
        function interceptHttp( $q, trafficCop ) {
            // Return the interceptor methods. They are all optional and get
            // added to the underlying promise chain.
            return({
                request: request,
                requestError: requestError,
                response: response,
                responseError: responseError
            });
            // ---
            // PUBLIC METHODS.
            // ---
            // Intercept the request configuration.
            function request( config ) {
                // NOTE: We know that this config object will contain a method as
                // this is the definition of the interceptor - it must accept a
                // config object and return a config object.
                trafficCop.startRequest( config.method );
                // Pass-through original config object.
                return( config );
            }
            // Intercept the failed request.
            function requestError( rejection ) {
                // At this point, we don't why the outgoing request was rejected.
                // And, we may not have access to the config - the rejection may
                // be an error object. As such, we'll just track this request as
                // a "GET".
                // --
                // NOTE: We can't ignore this one since our responseError() would
                // pick it up and we need to be able to even-out our counts.
                trafficCop.startRequest( "get" );
                // Pass-through the rejection.
                return( $q.reject( rejection ) );
            }
            // Intercept the successful response.
            function response( response ) {
                trafficCop.endRequest( extractMethod( response ) );
                // Pass-through the resolution.
                return( response );
            }
            // Intercept the failed response.
            function responseError( response ) {
                trafficCop.endRequest( extractMethod( response ) );
                // Pass-through the rejection.
                return( $q.reject( response ) );
            }
            // ---
            // PRIVATE METHODS.
            // ---
            // I attempt to extract the HTTP method from the given response. If
            // another interceptor has altered the response (albeit a very
            // unlikely occurrence), then we may not be able to access the config
            // object or the the underlying method. If this fails, we return GET.
            function extractMethod( response ) {
                try {
                    return( response.config.method );
                } catch ( error ) {
                    return( "get" );
                }
            }
        }
    }
);


  // I keep track of the total number of HTTP requests that have been initiated
  // and completed in the application. I work in conjunction with an HTTP
  // interceptor that pipes data from the $http service into get/end methods.
myApp.service(
    "trafficCop",
    function setupService() {
        // I keep track of the total number of HTTP requests that have been
        // initiated with the application.
        var total = {
            all: 0
        };
        // I keep track of the total number of HTTP requests that have been
        // initiated, but have not yet completed (ie, are still running).
        var pending = {
            all: 0
        };
        // Return the public API.
        return({
            pending: pending,
            total: total,
            endRequest: endRequest,
            startRequest: startRequest,
        });
        // ---
        // PUBLIC METHODS.
        // ---
        // I stop tracking the given HTTP request.
        function endRequest( httpMethod ) {
            pending.all--;
            if(pending.all < 1){
              jQuery(".preloadingContainer").slideUp(600);
              jQuery(".preloadingContainer .preloadingData").fadeOut(500);
            }
        }
        // I start tracking the given HTTP request.
        function startRequest( httpMethod ) {
            jQuery(".preloadingContainer .preloadingData").fadeIn(500);
            jQuery(".preloadingContainer").slideDown(600);
            total.all++;
            pending.all++;
        }
    }
);

myApp.run(['$rootScope', function ($rootScope) {

    $rootScope.messageModal = '';
    $rootScope.includeBtnModal = false;
    $rootScope.linkModal = null;
    $rootScope.textBtnModal = null;
    $rootScope.showModal = false;

    $rootScope.showMessageModal = function (message, includeBtn = false, link = null, textBtn = null){
        $rootScope.showModal = true;
        $rootScope.messageModal = message;
        $rootScope.includeBtnModal = includeBtn;
        
        if(includeBtn){
            $rootScope.linkModal = link;
            $rootScope.textBtnModal = textBtn;
        }
    }

    $rootScope.close_message = function(){
        $rootScope.showModal = false;
    }

}]);