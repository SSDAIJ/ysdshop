(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-005f104e"],{3550:function(t,s,a){"use strict";var i=a("d20d"),e=a.n(i);e.a},"366e":function(t,s,a){"use strict";var i=function(){var t=this,s=t.$createElement,a=t._self._c||s;return t.time?a("div",{staticClass:"active-count-down"},[a("van-count-down",{attrs:{format:"HH:mm:ss","use-slot":"",time:t.time},on:{change:t.onChangeDate,finish:t.onFinished}},[a("div",{staticClass:"row-center"},[t.timeData.days?a("div",{staticClass:"xs mr5"},[t._v(" "+t._s(t.timeData.days)+"天 ")]):t._e(),a("div",{staticClass:"item xs",style:{color:t.color,"background-color":t.bgColor}},[t._v(" "+t._s(t.timeData.hours)+" ")]),a("div",{staticClass:"primary",style:{margin:"0 3px",color:t.bgColor}},[t._v(" : ")]),a("div",{staticClass:"item xs",style:{color:t.color,"background-color":t.bgColor}},[t._v(" "+t._s(t.timeData.minutes)+" ")]),a("div",{staticClass:"primary",style:{margin:"0 3px",color:t.bgColor}},[t._v(" : ")]),a("div",{staticClass:"item xs",style:{color:t.color,"background-color":t.bgColor}},[t._v(" "+t._s(t.timeData.seconds)+" ")])])])],1):t._e()},e=[],r=(a("caad"),a("fb6a"),a("a9e3"),{props:{time:{type:Number,default:0},color:{type:String,default:"#fff"},bgColor:{type:String,default:"#FF2C3C"}},data:function(){return{timeData:{}}},methods:{onChangeDate:function(t){var s={};for(var a in t)s[a]=["hours","minutes","seconds"].includes(a)?s[a]=("0"+t[a]).slice(-2):t[a];this.timeData=s},onFinished:function(){this.$emit("finish")}}}),c=r,n=(a("3550"),a("2877")),o=Object(n["a"])(c,i,e,!1,null,"c1c2d62c",null),l=o.exports;s["a"]=l},"75de":function(t,s,a){},"92c5":function(t,s,a){"use strict";a.r(s);var i=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"group-details"},[a("div",{staticClass:"header"},[a("div",{staticClass:"content"},[a("div",{staticClass:"row-between"},[t._m(0),a("div",{staticClass:"rule xxs primary br60 row"},[a("van-icon",{staticClass:"mr5",attrs:{size:"17px",name:"question-o"}}),t._v("规则 ")],1)]),a("div",{staticClass:"xxl white mt10"},[t._v("拼团中")]),a("div",{staticClass:"xxs white mt10"},[t._v("邀请好友加入拼团 — 满员即发货")])])]),a("div",{staticClass:"main"},[a("group-team"),a("div",{staticClass:"goods bg-white"},[a("div",{staticClass:"count-down row-center"},[a("div",{staticClass:"sm mr10"},[t._v("距离结束02天")]),a("active-count-down",{attrs:{time:936e5},on:{finish:t.onFinished}})],1),a("div",{staticClass:"info row"},[a("div",{staticStyle:{flex:"none"}},[a("van-image",{attrs:{width:"90px",radius:"10rpx",height:"90px","lazy-load":"",src:"{{}}"}})],1),a("div",{staticClass:"ml10"},[a("div",{staticClass:"goods-name two-txt-cut mb5"},[t._v(" 儿童电话手表学生防水儿童电话手表学生防水多功能小孩子儿童手表男女智能电话手表手机多功能小孩子儿童手表男女智能电话手表手机 ")]),a("div",{staticClass:"goods-spec xs muted mb5"},[t._v("蓝色")]),a("div",{staticClass:"~row-between"},[a("div",{staticClass:"~primary"},[a("price-slice",{attrs:{"show-subscript":!0,"sub-script-class":"sm","first-class":"lg","second-class":"sm",price:900,weight:500,color:t.primaryColor}})],1)])])])]),t._m(1),t._m(2)],1)])},e=[function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"footprint row br60"},[a("img",{staticClass:"avator mr5",attrs:{src:""}}),a("span",{staticClass:"sm white one-txt-cut"},[t._v("蜡笔小新2分钟前拼2分钟前拼团成功2分钟前拼团成功！！！！团成功！！")])])},function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"btns column"},[a("button",{staticClass:"br60 white btn",attrs:{size:"lg"}},[t._v("邀请好友")]),a("button",{staticClass:"pain br60 primary",attrs:{size:"lg"}},[t._v(" 查看订单详情 ")])])},function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"goods-list"},[a("div",{staticClass:"title row-center"},[a("div",{staticClass:"line"}),a("div",{staticClass:"xxl"},[t._v("更多拼团商品")]),a("div",{staticClass:"line"})])])}],r=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"group-team bg-white"},[t._m(0),a("div",{staticClass:"row-center"},[a("div",{staticClass:"row avator-wrap"},t._l(3,(function(s){return a("div",{key:s,staticClass:"avator"},[a("div",{staticClass:"avator-icon"},[a("van-image",{attrs:{width:"100%",height:"100%",round:""}})],1),a("div",{staticClass:"br60 bg-primary white xxs tag"},[t._v("团长")])])})),0)])])},c=[function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"row-center title"},[a("div",{staticClass:"line"}),a("div",{staticClass:"ml10 mr10"},[t._v("您正在发起2人拼购")]),a("div",{staticClass:"line"})])}],n={},o=n,l=(a("ac4e"),a("2877")),d=Object(l["a"])(o,r,c,!1,null,"3ead4c6e",null),v=d.exports,u=v,m=a("366e"),C={name:"groupDetails",components:{GroupTeam:u,ActiveCountDown:m["a"]}},f=C,_=(a("ed82"),Object(l["a"])(f,i,e,!1,null,null,null));s["default"]=_.exports},ac4e:function(t,s,a){"use strict";var i=a("b5de"),e=a.n(i);e.a},b5de:function(t,s,a){},caad:function(t,s,a){"use strict";var i=a("23e7"),e=a("4d64").includes,r=a("44d2"),c=a("ae40"),n=c("indexOf",{ACCESSORS:!0,1:0});i({target:"Array",proto:!0,forced:!n},{includes:function(t){return e(this,t,arguments.length>1?arguments[1]:void 0)}}),r("includes")},d20d:function(t,s,a){},ed82:function(t,s,a){"use strict";var i=a("75de"),e=a.n(i);e.a}}]);
//# sourceMappingURL=chunk-005f104e.a07d2c8f.js.map