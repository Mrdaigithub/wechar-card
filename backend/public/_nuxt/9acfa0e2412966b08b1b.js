(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{193:function(t,e,a){var s=a(217);"string"==typeof s&&(s=[[t.i,s,""]]),s.locals&&(t.exports=s.locals);(0,a(16).default)("4ab9e36a",s,!0,{})},216:function(t,e,a){"use strict";var s=a(193);a.n(s).a},217:function(t,e,a){(t.exports=a(15)(!1)).push([t.i,"",""])},258:function(t,e,a){"use strict";a.r(e);var s={name:"EmployeeManagement",layout:"shop",data:function(){return{breadcrumbList:[{text:"主页",disabled:!1,href:"/shop"},{text:"店员管理",disabled:!0,href:"/shop/employeemanagement"}],employeeList:[{name:"张三",job:"店长",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/house.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三2",job:"店员",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/road.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三",job:"店长",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/house.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三2",job:"店员",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/road.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三",job:"店长",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/house.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三2",job:"店员",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/road.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三",job:"店长",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/house.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三2",job:"店员",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/road.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三",job:"店长",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/house.jpg",remarks:"备注备注备注备注备注备注备注"},{name:"张三2",job:"店员",createdDate:"2018-12-31",src:"https://cdn.vuetifyjs.com/images/employeeList/road.jpg",remarks:"备注备注备注备注备注备注备注"}],cardList:[{name:"1元优惠券",src:"https://cdn.vuetifyjs.com/images/employeeList/house.jpg"},{name:"1元优惠券",src:"https://cdn.vuetifyjs.com/images/employeeList/road.jpg"},{name:"1元优惠券",src:"https://cdn.vuetifyjs.com/images/employeeList/house.jpg"},{name:"1元优惠券",src:"https://cdn.vuetifyjs.com/images/employeeList/road.jpg"}],qrCodeDialog:!1,permissionDialog:!1,switchState:!0}},methods:{changePage:function(t){this.$router.push(t)},editPermission:function(t){this.permissionDialog=!0}}},i=(a(216),a(4)),r=Object(i.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("v-breadcrumbs",{attrs:{items:t.breadcrumbList,divider:">"}}),t._v(" "),a("v-container",{attrs:{fluid:"","grid-list-lg":""}},[a("v-layout",{attrs:{row:"",wrap:""}},t._l(t.employeeList,function(e,s){return a("v-flex",{key:s,attrs:{xs12:"",sm6:"",md6:"",lg4:""}},[a("v-card",{attrs:{color:"white"}},[a("v-layout",{attrs:{row:""}},[a("v-flex",{attrs:{xs5:""}},[a("v-img",{attrs:{src:"https://cdn.vuetifyjs.com/images/cards/halcyon.png",height:"125px",contain:""}})],1),t._v(" "),a("v-flex",{attrs:{xs7:""}},[a("v-card-title",{attrs:{"primary-name":""}},[a("div",[a("div",{staticClass:"headline"},[t._v(t._s(e.name))]),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("span",{attrs:{slot:"activator"},slot:"activator"},[t._v(t._s(e.job))]),t._v(" "),a("span",[t._v("人员岗位")])]),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("div",{attrs:{slot:"activator"},slot:"activator"},[t._v(t._s(e.createdDate))]),t._v(" "),a("span",[t._v("创建时间")])]),t._v(" "),a("v-tooltip",{attrs:{top:""}},[a("div",{attrs:{slot:"activator"},slot:"activator"},[t._v(t._s(e.remarks))]),t._v(" "),a("span",[t._v("备注")])])],1)])],1)],1),t._v(" "),a("v-divider",{attrs:{light:""}}),t._v(" "),a("v-card-actions",{staticClass:"pa-3"},[a("v-spacer"),t._v(" "),a("v-btn",{attrs:{icon:""},on:{click:function(a){t.editPermission(e)}}},[a("v-icon",[t._v("edit")])],1),t._v(" "),a("v-btn",{attrs:{icon:""}},[a("v-icon",[t._v("delete")])],1)],1)],1)],1)}),1)],1),t._v(" "),a("v-dialog",{attrs:{persistent:"","max-width":"500px"},model:{value:t.qrCodeDialog,callback:function(e){t.qrCodeDialog=e},expression:"qrCodeDialog"}},[a("v-card",[a("v-card-title",[a("span",[t._v("员工扫描此二维码以添加")]),t._v(" "),a("v-spacer")],1),t._v(" "),a("v-img",{attrs:{src:"http://upload.mnw.cn/2016/0126/1453768615784.jpg",contain:""}}),t._v(" "),a("v-card-actions",[a("v-btn",{attrs:{color:"primary",flat:""},on:{click:function(e){t.qrCodeDialog=!1}}},[t._v("关闭\n        ")])],1)],1)],1),t._v(" "),a("v-dialog",{attrs:{"max-width":"1200px"},model:{value:t.permissionDialog,callback:function(e){t.permissionDialog=e},expression:"permissionDialog"}},[a("v-card",[a("v-card-title",[a("span",[t._v("编辑员工可操作的卡券")]),t._v(" "),a("v-spacer")],1),t._v(" "),a("v-container",{attrs:{fluid:"","grid-list-lg":""}},[a("v-layout",{attrs:{row:"",wrap:""}},t._l(t.cardList,function(e,s){return a("v-flex",{key:s,attrs:{xs12:"",sm6:"",md6:"",lg4:""}},[a("v-card",{attrs:{color:"white"}},[a("v-layout",{attrs:{row:""}},[a("v-flex",{attrs:{xs7:""}},[a("v-card-title",{attrs:{"primary-name":""}},[a("div",[a("div",{staticClass:"headline"},[t._v(t._s(e.name))])])])],1),t._v(" "),a("v-flex",{attrs:{xs5:""}},[a("v-img",{attrs:{src:"https://cdn.vuetifyjs.com/images/cards/halcyon.png",height:"125px",contain:""}})],1)],1),t._v(" "),a("v-divider",{attrs:{light:""}}),t._v(" "),a("v-card-actions",[a("v-switch",{model:{value:t.switchState,callback:function(e){t.switchState=e},expression:"switchState"}})],1)],1)],1)}),1)],1)],1)],1),t._v(" "),a("v-btn",{attrs:{fixed:"",dark:"",fab:"",bottom:"",left:"",color:"red"},on:{click:function(e){t.qrCodeDialog=!0}}},[a("v-icon",[t._v("add")])],1)],1)},[],!1,null,"23ba4583",null);r.options.__file="employeeManagement.vue";e.default=r.exports}}]);