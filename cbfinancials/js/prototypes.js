String.prototype.getCnLen = function(){ return this.replace(/[^\x00-\xff]/g,"--").length;};
String.prototype.getSame = function(num) { var tmpArr = []; for (var i = 0; i < num; i++) tmpArr.push(this); return tmpArr.join(""); };
String.prototype.getNumStr = function(){ return this.replace(/[^\d]/g,"");};
String.prototype.getEnStr = function(){ return this.replace(/[^A-Za-z]/g,""); };
String.prototype.getCnStr = function(){ return this.replace(/[^\u4e00-\u9fa5\uf900-\ufa2d]/g,"");};
String.prototype.left = function(n){ return this.slice(0,n);};
String.prototype.right = function(n){ return this.slice(this.length-n);};
String.prototype.mid = function(start, len) { return this.substr(start, len); };
String.prototype.trim = function(){ return this.replace(/(^\s+)|(\s+$)/g,""); };
String.prototype.lTrim = function(){ return this.replace(/^\s+/g,""); } ;
String.prototype.rTrim = function(){ return this.replace(/\s+$/g,""); };
String.prototype.htmlEncode = function(){ var re = this; var q1 = [/\x26/g,/\x3C/g,/\x3E/g,/\x20/g]; var q2 = ["&amp;","&lt;","&gt;","&nbsp;"]; for(var i=0;i<q1.length;i++) re = re.replace(q1[i],q2[i]); return re;};
String.prototype.unicode = function(){ var strText = ""; for (var i=0; i<this.length; i++) strText += "&#" + this.charCodeAt(i) + ";"; return strText;};
String.prototype.replaceEmpty = function() {  return this.replace(/\s+/g," ");};
String.prototype.format = function() { var args = arguments; return this.replace(/{(\d+)}/g, function() { return args[arguments[1]]; }); };
String.prototype.inStr = function(str) { return str.indexOf(this) > -1; };
String.prototype.toDateTime = function() { var val = this.replace(/[-]/g,"/"); if (val.isDate() || val.isDateTime()) return new Date(Date.parse(val)); var r = this.match(/(\d+)/); if (r) return new Date(parseInt(r)); return new Date(val); };
String.prototype.sub = function(n,ext) { ext = ext || ''; var r = /[^\x00-\xff]/g; if(this.replace(r, "mm").length <= n) return this; var m = Math.floor(n/2); for(var i=m; i<this.length; i++) { if(this.substr(0, i).replace(r, "mm").length>=n) { return this.substr(0, i) + ext; } } return this; };
String.prototype.append = function(str){ return this.concat(str); };
String.prototype.template = function(o, f) { var s = this, patternExpr = /{([^{}]*)}/g; s = s.replace(patternExpr, function(s, a) { if (!a) return ''; try { var r = eval("with(o){" + s + "}"); return Object.defined(r) ? (f ? f(r) : r) : ''; } catch (ex) { return "{" + s + "}"; } }); return s; };
String.prototype.quote = function () {
    var reEscapeable = /[\"\\\x00-x1f\x7f-\x9f]/g;
    var SpecialChars = {
        '\b': '\\b', '\t': '\\t', '\n': '\\n',      // 转义字符
        '\f': '\\f', '\r': '\\r', '\\': '\\\\'
    };
    if (reEscapeable.test(this)) {
        return '"' + this.replace(reEscapeable, function (a) {
            var c = SpecialChars[a];
            if (typeof c === 'string') {
                return c;
            };
            c = a.charCodeAt();
            return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
        }) + '"';
    };
    return '"' + this + '"';
};
String.prototype.clearHtml = function () {
    return this.replace(/<[^>]*>/g, '');
};
String.prototype.isNum = function() { return /^[1-9][0-9]*(.[0-9]+)?$/.test(this); };
String.prototype.isInt = function() { return /^[-\+]?\d+$/.test(this); };
String.prototype.isEmail = function() { return /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(this); };
String.prototype.isMobile = function () { return /^(13|15|18)\d{9}$/.test(this); };
String.prototype.isPhone = function() { return /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/.test(this); };
String.prototype.isCn = function() { return /^[\u0391-\uFFE5]+$/.test(this); };
String.prototype.isEn = function() { return /^[A-Za-z]+$/.test(this); };
String.prototype.isZip = function() { return /^[1-9]\d{5}$/.test(this); };
String.prototype.isUrl = function() { return /^(http(s)?:\/\/)?[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^\"\"])*$/.test(this); };
String.prototype.isDateTime = function() { var r = this.replace(/(^\s*)|(\s*$)/g, "").match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/); if (r == null) return false; var d = new Date(r[1], r[3] - 1, r[4], r[5], r[6], r[7]); return (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4] && d.getHours() == r[5] && d.getMinutes() == r[6] && d.getSeconds() == r[7]); };
String.prototype.isDate = function() { var r = this.replace(/(^\s*)|(\s*$)/g, "").match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/); if (r == null) return false; var d = new Date(r[1], r[3] - 1, r[4]); return (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4]); };
String.prototype.isUserName = function() { return (this.replace(/\w/g, "").length == 0); };
String.prototype.isJSON = function() { var str = this; if(str.blank() || str.empty()) { return false; } str = this.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, ''); return (/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(str); };

Date.prototype.format = function(fmt) { var o = { "M+": this.getMonth() + 1, "d+": this.getDate(), "h+": this.getHours() % 12 == 0 ? 12 : this.getHours() % 12, "H+": this.getHours(), "m+": this.getMinutes(), "s+": this.getSeconds(), "q+": Math.floor((this.getMonth() + 3) / 3), "S": this.getMilliseconds() }; var week = { "0": "\u65e5", "1": "\u4e00", "2": "\u4e8c", "3": "\u4e09", "4": "\u56db", "5": "\u4e94", "6": "\u516d" }; if (/(y+)/.test(fmt)) { fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length)); }; if (/(E+)/.test(fmt)) { fmt = fmt.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "\u661f\u671f" : "\u5468") : "") + week[this.getDay() + ""]); }; for (var k in o) { if (new RegExp("(" + k + ")").test(fmt)) { fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length))); }; }; return fmt; };
Date.prototype.dateAdd = function(interval, number) { var d = new Date(this); var k = { 'y': 'FullYear', 'q': 'Month', 'm': 'Month', 'w': 'Date', 'd': 'Date', 'h': 'Hours', 'n': 'Minutes', 's': 'Seconds', 'ms': 'MilliSeconds' }; var n = { 'q': 3, 'w': 7 }; eval('d.set' + k[interval] + '(d.get' + k[interval] + '()+' + ((n[interval] || 1) * number) + ')'); return d; };
Date.prototype.dateDiff = function(strInterval, dtEnd) { var dtStart = this; switch (strInterval) { case 's': return parseInt((dtEnd - dtStart) / 1000); case 'n': return parseInt((dtEnd - dtStart) / 60000); case 'h': return parseInt((dtEnd - dtStart) / 3600000); case 'd': return parseInt((dtEnd - dtStart) / 86400000); case 'w': return parseInt((dtEnd - dtStart) / (86400000 * 7)); case 'm': return (dtEnd.getMonth() + 1) + ((dtEnd.getFullYear() - dtStart.getFullYear()) * 12) - (dtStart.getMonth() + 1); case 'y': return dtEnd.getFullYear() - dtStart.getFullYear(); }; };
Date.prototype.dateAdd = function(strInterval, Number) { var dtTmp = this; switch (strInterval) { case 's': return new Date(Date.parse(dtTmp) + (1000 * Number)); case 'n': return new Date(Date.parse(dtTmp) + (60000 * Number)); case 'h': return new Date(Date.parse(dtTmp) + (3600000 * Number)); case 'd': return new Date(Date.parse(dtTmp) + (86400000 * Number)); case 'w': return new Date(Date.parse(dtTmp) + ((86400000 * 7) * Number)); case 'q': return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number * 3, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds()); case 'm': return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds()); case 'y': return new Date((dtTmp.getFullYear() + Number), dtTmp.getMonth(), dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds()); }; };

Array.prototype.indexOf = function(item) { var length = this.length; for (var i=0; i < length; i++) if (this[i] == item) return i; return -1; };