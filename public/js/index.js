angular.module('contabilizateApp', [])
	.controller('ContributorsCtrl', ['$scope', function ($scope) {
		$scope.init = function(){
			$scope.contribuyente = {};
			$scope.contribuyenteEdit = {};
		}
	}]);;
