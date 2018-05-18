/**
 * Created by herman on 2016/11/28.
 */
/*
 * js获取cookie值
 * @param cookie_name cookie值
 * */
function getCookie(cookie_name) {
    var allcookies = document.cookie;
    var cookie_pos = allcookies.indexOf(cookie_name);   //索引的长度
// 如果找到了索引，就代表cookie存在，
// 反之，就说明不存在
    if (cookie_pos != -1) {
// 把cookie_pos放在值的开始，只要给值加1即可。
        cookie_pos += cookie_name.length + 1;      //这里我自己试过，容易出问题，所以请大家参考的时候自己好好研究一下。。。
        var cookie_end = allcookies.indexOf(";", cookie_pos);
        if (cookie_end == -1) {
            cookie_end = allcookies.length;
        }
        var value = unescape(allcookies.substring(cookie_pos, cookie_end)); //这里就可以得到你想要的cookie的值了。。。
        value = value.replace(/\"/g,'');
    }
    return value;
}

var cookie_val = getCookie('116CS_id');
var Url = window.location.href;
if(cookie_val!= undefined){
    Url = Url.replace(/.html/i,"/uid/"+cookie_val+".html");
}
var meta = document.getElementsByTagName('meta');
var share_desc = '';
for(i in meta){
    if(typeof meta[i].name!="undefined"&&meta[i].name.toLowerCase()=="description"){
        share_desc = meta[i].content;
    }
}
window._bd_share_config = {
    common: {
        bdText: share_desc,
        bdDesc: '',
        bdUrl:  Url,
        bdPic: ''
    },
    share: [{
        "bdSize": 16
    }],
    slide: [{
        bdImg: 0,
        bdPos: "left",
        bdTop: 300
    }],
    // image: [{
    //     viewType: 'list',
    //     viewPos: 'top',
    //     viewColor: 'black',
    //     viewSize: '16',
    //     viewList: ['qzone', 'tsina', 'huaban', 'tqq', 'renren']
    // }],
    selectShare: [{
        "bdselectMiniList": ['qzone', 'tqq', 'kaixin001', 'bdxc', 'tqf']
    }]
}
with (document)0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion=' + ~(-new Date() / 36e5)];