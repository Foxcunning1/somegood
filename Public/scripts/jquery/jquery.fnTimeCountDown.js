/**Zepto Or Jquery 倒计时插件
*/
$.extend($.fn,{
        fnTimeCountDown:function(d){
			 var defaults = {
				endTime: '2050/01/01 00:00:00',//倒计时结束的时间
                endTipsTxt: '已结束！',//倒计时结束显示的文本
            };
            var d = $.extend(defaults, d);
            this.each(function(){
                var $this = $(this);
				$this.append("<span class=\"year\"><em></em>年</span>");
				$this.append("<span class=\"month\"><em></em>月</span>");
				$this.append("<span class=\"day\"><em></em>日</span>");
				$this.append("<span class=\"hour\"><em></em>时</span>");
				$this.append("<span class=\"mini\"><em></em>分</span>");
				$this.append("<span class=\"sec\"><em></em>秒</span>");
				$this.append("<span class=\"hm\"><em></em></span>");
                var o = {
                    hm: $this.find(".hm em"),
                    sec: $this.find(".sec em"),
                    mini: $this.find(".mini em"),
                    hour: $this.find(".hour em"),
                    day: $this.find(".day em"),
                    month:$this.find(".month em"),
                    year: $this.find(".year em")
                };
                var f = {
                    haomiao: function(n){
                        if(n < 10)return "00" + n.toString();
                        if(n < 100)return "0" + n.toString();
                        return n.toString();
                    },
                    zero: function(n){
                        var _n = parseInt(n, 10);//解析字符串,返回整数
                        if(_n > 0){
                            if(_n <= 9){
                                _n = "0" + _n
                            }
                            return String(_n);
                        }else{
                            return "00";
                        }
                    },
                    dv: function(){
                        //d = d || Date.UTC(2050, 0, 1); //如果未定义时间，则我们设定倒计时日期是2050年1月1日
                        var _d = $this.data("end") || d.endTime;
                        var now = new Date(),
                            endDate = new Date(_d);
                        //现在将来秒差值
                        //alert(future.getTimezoneOffset());
                        var dur = (endDate - now.getTime()) / 1000 , mss = endDate - now.getTime() ,pms = {
                            hm:"000",
                            sec: "00",
                            mini: "00",
                            hour: "00",
                            day: "00",
                            month: "00",
                            year: "0"
                        };
                        if(mss > 0){
                            pms.hm = f.haomiao(mss % 1000);
                            pms.sec = f.zero(dur % 60);
                            pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "00";
                            pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "00";
                            pms.day = Math.floor((dur / 86400)) > 0? f.zero(Math.floor((dur / 86400)) % 30) : "00";
                            //月份，以实际平均每月秒数计算
                            pms.month = Math.floor((dur / 2629744)) > 0? f.zero(Math.floor((dur / 2629744)) % 12) : "00";
                            //年份，按按回归年365天5时48分46秒算
                            pms.year = Math.floor((dur / 31556926)) > 0? Math.floor((dur / 31556926)) : "0";
                        }else{
                            pms.year=pms.month=pms.day=pms.hour=pms.mini=pms.sec="00";
                            pms.hm = "000";
							o.hm.parent("span.hm").addClass("end").text(d.endTipsTxt);
							o.sec.parent("span.sec").remove();
							o.mini.parent("span.mini").remove();
							o.hour.parent("span.hour").remove();
							o.day.parent("span.day").remove();
							o.month.parent("span.month").remove();
							o.year.parent("span.year").remove();
                            //alert('结束了');
                            return;
                        }
                        return pms;
                    },
                    ui: function(){
                        if(o.hm&&f.dv().hm!="000"){
                            o.hm.html(f.dv().hm);
                        }
                        if(o.sec&&f.dv().sec!="00"){
                            o.sec.html(f.dv().sec);
                        }else{
							if(f.dv().sec=="00"&&f.dv().mini=="00"&&f.dv().hour=="00"&&f.dv().day=="00"&&f.dv().month=="00"&&f.dv().year=="0"){
								o.sec.parent("span.sec").remove();
							}else{
								o.sec.html(f.dv().sec);
							}
						}
                        if(o.mini&&f.dv().mini!="00"){
                            o.mini.html(f.dv().mini);
						}else{
							if(f.dv().mini=="00"&&f.dv().hour=="00"&&f.dv().day=="00"&&f.dv().month=="00"&&f.dv().year=="0"){
								o.mini.parent("span.mini").remove();
							}else{
								o.mini.html(f.dv().mini);
							}
						}
                        if(o.hour&&f.dv().hour!="00"){
                            o.hour.html(f.dv().hour);
						}else{
							if(f.dv().hour=="00"&&f.dv().day=="00"&&f.dv().month=="00"&&f.dv().year=="0"){
								o.hour.parent("span.hour").remove();
							}else{
								o.hour.html(f.dv().hour);
							}
						}
                        if(o.day&&f.dv().day!="00"){
                            o.day.html(f.dv().day);
						}else{
							if(f.dv().day=="00"&&f.dv().month=="00"&&f.dv().year=="0"){
								o.day.parent("span.day").remove();
							}else{
								o.day.html(f.dv().day);
							}
						}
                        if(o.month&&f.dv().month!="00"){
                            o.month.html(f.dv().month);
						}else{
							if(f.dv().month=="00"&&f.dv().year=="0"){
								o.month.parent("span.month").remove();
							}else{
								o.month.html(f.dv().month);
							}
						}
                        if(o.year&&f.dv().year!="0"){
                            o.year.html(f.dv().year);
						}else{
							if(f.dv().month=="00"&&f.dv().year=="0"){
								o.year.parent("span.year").remove();
							}else{
								o.year.html(f.dv().year);
							}
						}
                        setTimeout(f.ui, 1);
                    }
                };
                f.ui();
            });
        }
    });