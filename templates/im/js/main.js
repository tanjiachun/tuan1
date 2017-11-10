/**
 * 主要业务逻辑相关
 */
var userUID = readCookie("uid");
/**
 * 实例化
 * @see module/base/js
 */
var yunXin = new YX(userUID);
var toID=readCookie("toID").toLocaleLowerCase();
console.log(toID);
yunXin.openChatBox(toID,'p2p');


