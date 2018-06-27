$(function () {
    /*1.分类列表分页展示*/
    var currPage = 1;
    var render = function(){
        getCategoryFirstData({
            page: currPage,
            pageSize: 10
        }, function (data) {
            /*渲染页面*/
            $('tbody').html(template('template',data));
            setPaginator(data.page,Math.ceil(data.total/data.size),render);
        });
    }
    render();
    /*2.分页展示*/
    var setPaginator = function(pageCurr,pageSum,callback){
        /*获取需要初始的元素 使用bootstrapPaginator方法*/
        $('.pagination').bootstrapPaginator({
            bootstrapMajorVersion:3,
            size:'small',
            currentPage:pageCurr,
            totalPages:pageSum,
            onPageClicked:function(event, originalEvent, type, page){
                currPage = page;
                callback && callback();
            }
        });
    }

    /*3.添加一级分类功能*/
    $('#addBtn').on('click',function () {
       /*显示模态框*/
       $('#addModal').modal('show');
    });
    /*进行表单校验*/
    $('#form').bootstrapValidator({
        /*默认样式*/
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        /*设置校验属性*/
        fields:{
            categoryName:{
                validators: {
                    notEmpty: {
                        message: '一级分类名称不能为空'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        console.log(0);
        e.preventDefault();
        /*如果点击需要校验  点击的按钮必须是提交按钮  并且和当前表单关联*/
        /*校验成功后的点击事件  完成数据的提交*/
        var $form = $(e.target);
        $.ajax({
            type:'post',
            url:'/category/addTopCategory',
            data:$form.serialize(),
            dataType:'json',
            success:function (data) {
                if(data.success){
                    /*关闭模态框*/
                    $('#addModal').modal('hide');
                    /*渲染第一页*/
                    currPage = 1;
                    render();
                    /*重置表单*/
                    $form.data('bootstrapValidator').resetForm();
                    $form.find('input').val('');
                }
            }
        });
    });


});
/*纯粹的获取数据*/
var getCategoryFirstData = function (params, callback) {
    $.ajax({
        type: 'get',
        url: '/category/queryTopCategoryPaging',
        data: params,
        dataType: 'json',
        success: function (data) {
            callback && callback(data);
        }
    });
}