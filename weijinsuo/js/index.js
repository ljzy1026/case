$(function () {
	//动态轮播图
    banner();
    /*移动端页签*/
    initMobileTab();
    // 初始化工具提示
    $('[data-toggle="tooltip"]').tooltip()
});
var banner = function () {
    /*1.获取轮播图数据    ajax */
    /*2.根据数据动态渲染  根据当前设备  屏幕宽度判断 */
    /*3.测试功能 页面尺寸发生改变重新渲染*/
    /*4.移动端手势切换  touch*/

    /*做数据缓存*/
    var getData = function (callback) {
        if(window.data){
            callback && callback(window.data);
        }else {
            /*1.获取轮播图数据*/
            $.ajax({
                type:'get',
                url:'js/data.json',
                dataType:'json',
                data:'',
                success:function (data) {
                    window.data = data;
                    callback && callback(window.data);
                }
            });
        }
    }
    var render = function () {
        getData(function (data) {
            /*2.根据数据动态渲染  根据当前设备  屏幕宽度判断 */
            var isMobile = $(window).width() < 768 ? true : false;
            var pointHtml = template('pointTemplate',{list:data});
            //console.log(pointHtml);
            var imageHtml = template('imageTemplate',{list:data,isMobile:isMobile});
            //console.log(imageHtml);
            $('.carousel-indicators').html(pointHtml);
            $('.carousel-inner').html(imageHtml);
        });
    }
    /*3.测试功能 页面尺寸发生改变事件*/
    $(window).on('resize',function () {
        render();
        /*通过js主动触发某个事件*/
    }).trigger('resize');
    /*4.移动端手势切换*/
    var startX = 0;
    var distanceX = 0;
    var isMove = false;
    /*originalEvent 代表js原生事件*/
    $('.wjs_banner').on('touchstart',function (e) {
        startX = e.originalEvent.touches[0].clientX;
    }).on('touchmove',function (e) {
        var moveX = e.originalEvent.touches[0].clientX;
        distanceX = moveX - startX;
        isMove = true;
    }).on('touchend',function (e) {
        /*距离足够 50px 一定要滑动过*/
        if(isMove && Math.abs(distanceX) > 50){
            if(distanceX < 0){
                //console.log('next');
                $('.carousel').carousel('next');
            }
            else {
                //console.log('prev');
                $('.carousel').carousel('prev');
            }
        }
        startX = 0;
        distanceX = 0;
        isMove = false;
    });
}
var initMobileTab = function () {
    //1.解决换行问题
    var $navTabs = $('.wjs_product .nav-tabs');
    var width = 0;
    $navTabs.find('li').each(function (i,item) {
        // 得把他得magin算上
        var liWidth = $(this).outerWidth(true);
        width += liWidth;
    });
    console.log(width);
    $navTabs.width(width);

    /*2.修改结构使之区域滑动的结构*/
    new IScroll($('.nav-tabs-parent').get(0),{
        scrollX:true,
        scrollY:false,
        click:true
    });
    //3.移除默认touchmove事件
    $('.nav-tabs-parent').on('touchmove',function (e) {
        e.originalEvent.preventDefault();
    })
}

















    