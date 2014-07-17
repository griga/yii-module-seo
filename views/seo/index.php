<?php
/** Created by griga at 08.07.2014 | 21:58.
 *
 */

cs()->registerPackage('angular.resource');
cs()->registerScriptFile('/themes/common/js/commerce/common.js');
$this->breadcrumbs = [
    t('Seo module') => '/seo',
];
?>
<style type="text/css">
    #seo-app .well{
        position: relative;
        min-height: 100px
    }
    #seo-app .table td{
        vertical-align: middle;
    }
</style>
<div class="col-sm-12" ng-app="SeoAdmin" id="seo-app" >
    <h3><?= t('Seo module') ?></h3>

    <div class="well" ng-controller="ConfigCtrl">
        <commerce-loader loading="loading"></commerce-loader>
        <commerce-overlay loading="loading"></commerce-overlay>
        <h4><?= ts('Seo Config') ?></h4>
        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th class="col-sm-3">key</th>
                <th class="col-sm-8">value</th>
                <th class="col-sm-1"><i class="glyphicon glyphicon-plus" ng-click="addModel()"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="model in newModels" >
                <td>
                    <textarea ng-model="model.key" class="form-control"></textarea>
                    <commerce-error value="model.errors.key"></commerce-error>
                </td>
                <td>
                    <multilingual-control model="model" field="value" ></multilingual-control>
                    <commerce-error value="model.errors.value"></commerce-error>
                </td>
                <td>
                    <i class="glyphicon glyphicon-ok" ng-click="create(model)"></i>
                    <i class="glyphicon glyphicon-trash" ng-click="cancelCreate(model)"></i>
                </td>
            </tr>
            <tr ng-repeat="model in models| orderBy: 'key'">
                <td><span ng-hide="model.edit">{{model.key}}</span>
                    <textarea ng-show="model.edit" ng-model="model.key" class="form-control"></textarea></td>
                    <commerce-error value="model.errors.key"></commerce-error>
                <td>
                    <span ng-hide="model.edit" ng-bind="model.value_<?=Lang::get()?>"></span>
                    <multilingual-control ng-show="model.edit" model="model" field="value" ></multilingual-control>
                    <commerce-error value="model.errors.value"></commerce-error>
                </td>
                <td>
                    <i class="glyphicon glyphicon-edit" ng-click="edit(model)" ng-hide="model.edit"></i>
                    <i class="glyphicon glyphicon-trash" ng-click="remove(model)" ng-hide="model.edit"></i>
                    <i class="glyphicon glyphicon-ok" ng-click="save(model)" ng-show="model.edit"></i>
                    <i class="glyphicon glyphicon-ban-circle" ng-click="cancel(model)" ng-show="model.edit"></i>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

    angular.module('SeoAdmin', ['ngResource','commerce.common'])
        .constant('SeoConfig', <?= CJSON::encode(Seo::ngConfig()) ?>)
        .constant('LangConfig', <?= CJSON::encode([
            'languages'=>Lang::getLanguages(),
            'applicationLang'=>Lang::get()
        ]) ?>)
        .factory('Config', function ($resource) {
            return $resource('/admin/api/seo-config/:id/',
                {id: '@id'});
        })
        .controller('ConfigCtrl', function ($scope, Config) {
            $scope.loading = true;
            $scope.models = Config.query({}, function(){
                $scope.loading = false;
            });
            $scope.newModels = [];
            $scope.addModel = function(){
                $scope.newModels.push(new Config({  edit: true }));
            };
            $scope.cancelCreate = function(model){
                $scope.newModels.splice($scope.newModels.indexOf(model), 1)
            };
            $scope.create = function(model){
                $scope.loading = true;

                model.$save(function(config){
                    config.edit = false;
                    $scope.newModels.splice($scope.newModels.indexOf(model), 1)
                    $scope.models.push(config);
                    $scope.loading = false;
                }, function(resp){
                    $scope.loading = false;
                    model.errors = resp.data
                });
            };
            $scope.edit = function(model){
                model.backup = angular.copy( model);
                model.edit = true;
            };
            $scope.remove = function(model){
                $scope.loading = true;
                model.$delete(function(){
                    $scope.models.splice($scope.models.indexOf(model), 1)
                    $scope.loading = false;
                });
            };
            $scope.cancel = function(model){
                angular.copy(model.backup, model)
            };
            $scope.save = function(model){
                $scope.loading = true;
                model.$save(function(){
                    model.edit = false;
                    $scope.loading = false;
                });
            }
        })
        .directive('multilingualControl',function(LangConfig){
            return {
                restrict: 'E',
                replace: true,
                template:
                    '<div>' +
                        '<div class="ml-handlers btn-group pull-right ">' +
                            '<button ng-repeat="(key, language) in languages" ng-class="{\'btn-success\':key==selectedLang}" ng-click="select(key)" class="btn btn-xs btn-default btn-ml">{{language}}</button>' +
                        '</div>' +
                        '<textarea ng-repeat="(key, language) in languages" ng-model="model[(\'value_\' + key )]" ng-show="key==selectedLang" class="form-control"></textarea>' +
                    '</div>',
                scope:{
                    model: '=',
                    field: '@'
                },
                link: function(scope, element, attrs){
                    scope.languages = LangConfig.languages;
                    scope.selectedLang = LangConfig.applicationLang;
                    scope.select = function(key){
                        scope.selectedLang = key;
                    };
                }
            }
        })
</script>
