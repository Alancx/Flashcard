/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : hmall

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-11-10 17:12:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tb_apptoken
-- ----------------------------
DROP TABLE IF EXISTS `tb_apptoken`;
CREATE TABLE `tb_apptoken` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL COMMENT '对应tb_user_id',
  `Token` varchar(255) NOT NULL,
  `Stoken` varchar(255) NOT NULL,
  `StoreId` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `AppToken` varchar(255) NOT NULL COMMENT 'app授权token',
  `ExpDate` int(11) NOT NULL COMMENT '过期时间',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_apptoken
-- ----------------------------
INSERT INTO `tb_apptoken` VALUES ('9', '8', 'rhbnja145862596121', 'nPyEo49507333966', '1009', '083a90ad68f2f6a66df485eb55780481', '1498816516');
INSERT INTO `tb_apptoken` VALUES ('10', '11', 'rhbnja145862596121', 'hFeGH49534882642', '1011', '302c82e1332d96aca5a35c7a3de38e81', '1498720700');
INSERT INTO `tb_apptoken` VALUES ('11', '18', 'rhbnja145862596121', '0', '0', '8edddbf6d0ff313ad390553721d3b847', '1501301857');
INSERT INTO `tb_apptoken` VALUES ('6', '1', 'rhbnja145862596121', '0', '0', 'b4a05bd66852e480114d75fe1f2b1f5f', '1500114442');
INSERT INTO `tb_apptoken` VALUES ('12', '15', 'rhbnja145862596121', 'vYfwa49553178058', '1014', '9d612e9cfd595e7b6ad3cc9a12771801', '1496460505');
INSERT INTO `tb_apptoken` VALUES ('13', '9', 'rhbnja145862596121', '0', '0', 'bcedc84070a72d3f4a456593f1aae3f7', '1501913586');
INSERT INTO `tb_apptoken` VALUES ('14', '19', 'rhbnja145862596121', '0', '0', 'f30c644e813b989156bbb0a307500805', '1498720505');
INSERT INTO `tb_apptoken` VALUES ('15', '12', 'rhbnja145862596121', 'NAUlV49541606740', '1012', '88f2c8f5a56dadb76bbf291324063f40', '1498815852');
INSERT INTO `tb_apptoken` VALUES ('16', '10', 'rhbnja145862596121', 'oXTdy49509119968', '1010', '065edb4bff1a262af453a26066a5f74d', '1498271579');
INSERT INTO `tb_apptoken` VALUES ('17', '22', 'rhbnja145862596121', '0', '0', 'f92e04cb74a2c67a6bd395381c9a7fb2', '1500866507');
INSERT INTO `tb_apptoken` VALUES ('18', '13', 'rhbnja145862596121', '0', '0', '8113673a1841636dddee0439fd6cf407', '1500002542');
INSERT INTO `tb_apptoken` VALUES ('19', '23', 'rhbnja145862596121', '0', '0', '8501d0d4f9ec43e2e9e730bfe464a547', '1500437737');

-- ----------------------------
-- Table structure for tb_groupmanger
-- ----------------------------
DROP TABLE IF EXISTS `tb_groupmanger`;
CREATE TABLE `tb_groupmanger` (
  `GroupId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(100) NOT NULL COMMENT '分组名称',
  `Remarks` varchar(200) DEFAULT NULL COMMENT '备注',
  `InputId` int(11) NOT NULL COMMENT '添加人ID',
  `InputName` int(11) NOT NULL,
  `CreateDate` int(11) DEFAULT NULL COMMENT '添加时间',
  `LastUpdateDate` int(11) DEFAULT NULL,
  `token` varchar(30) NOT NULL COMMENT '商户标识',
  `stoken` varchar(255) DEFAULT '0' COMMENT '开店人标识',
  PRIMARY KEY (`GroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='分组管理表';

-- ----------------------------
-- Records of tb_groupmanger
-- ----------------------------
INSERT INTO `tb_groupmanger` VALUES ('7', '超级管理组', null, '0', '0', '1495073384', '1495073384', 'rhbnja145862596121', 'nPyEo49507333966');
INSERT INTO `tb_groupmanger` VALUES ('8', '超级管理组', null, '1', '0', null, null, 'rhbnja145862596121', '0');
INSERT INTO `tb_groupmanger` VALUES ('9', '超级管理组', null, '0', '0', '1495091109', '1495091109', 'rhbnja145862596121', 'oXTdy49509119968');
INSERT INTO `tb_groupmanger` VALUES ('10', '超级管理组', null, '0', '0', '1495348813', '1495348813', 'rhbnja145862596121', 'hFeGH49534882642');
INSERT INTO `tb_groupmanger` VALUES ('11', '超级管理组', null, '0', '0', '1495416009', '1495416009', 'rhbnja145862596121', 'NAUlV49541606740');
INSERT INTO `tb_groupmanger` VALUES ('12', '超级管理组', null, '0', '0', '1495531729', '1495531729', 'rhbnja145862596121', 'vYfwa49553178058');
INSERT INTO `tb_groupmanger` VALUES ('13', '超级管理组', null, '0', '0', '1495618538', '1495618538', 'rhbnja145862596121', 'QTNFg49561856205');
INSERT INTO `tb_groupmanger` VALUES ('14', '超级管理组', null, '0', '0', '1495766902', '1495766902', 'rhbnja145862596121', 'lPduK49576693002');
INSERT INTO `tb_groupmanger` VALUES ('15', '超级管理组', null, '0', '0', '1496721383', '1496721383', 'rhbnja145862596121', 'Rnmkm49672133165');
INSERT INTO `tb_groupmanger` VALUES ('16', '超级管理组', null, '0', '0', '1496730943', '1496730943', 'rhbnja145862596121', 'tyivU49673093021');

-- ----------------------------
-- Table structure for tb_menu
-- ----------------------------
DROP TABLE IF EXISTS `tb_menu`;
CREATE TABLE `tb_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MenuId` varchar(50) NOT NULL COMMENT '菜单ID',
  `ParentId` varchar(50) NOT NULL COMMENT '上级菜单ID',
  `RootId` varchar(50) NOT NULL,
  `MenuName` varchar(50) NOT NULL COMMENT '菜单名称',
  `Sort` varchar(50) NOT NULL COMMENT '排序',
  `Grade` varchar(10) NOT NULL COMMENT '菜单等级',
  `IsEnable` enum('0','1') DEFAULT '1' COMMENT '是否可用',
  `MenuUrl` varchar(200) DEFAULT NULL COMMENT '菜单跳转URL',
  `MenuController` varchar(200) DEFAULT NULL COMMENT '控制器名称',
  `MenuIcon` varchar(200) DEFAULT NULL COMMENT '菜单图标',
  `MenuType` enum('admin','user','store') DEFAULT NULL COMMENT '菜单类型，admin平台菜单 user商户菜单',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2698 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of tb_menu
-- ----------------------------
INSERT INTO `tb_menu` VALUES ('70', 'm01', '0', '0', '主页', '01', '1', '1', 'Index/index', 'INDEX', 'fa fa-home', 'user');
INSERT INTO `tb_menu` VALUES ('71', 'm02', '0', '0', '基础设置', '02', '1', '1', 'BASE', '', 'fa fa-barcode', 'user');
INSERT INTO `tb_menu` VALUES ('72', 'm03', '0', '0', '员工管理', '03', '1', '1', 'YUANGONG', '', 'fa fa-th', 'user');
INSERT INTO `tb_menu` VALUES ('73', 'm0503', 'm05', '0', '盘点管理', '05-07', '2', '1', '', 'CANGKU', 'fa fa-cart-plus', 'user');
INSERT INTO `tb_menu` VALUES ('74', 'm0601', 'm06', '0', '优惠券管理', '06-01', '2', '1', 'Products/coupons', 'CUXIAO', 'fa fa-users', 'user');
INSERT INTO `tb_menu` VALUES ('76', 'm09', '0', '0', '会员管理', '09', '1', '1', 'HUIYUAN', '', 'fa fa-users', 'user');
INSERT INTO `tb_menu` VALUES ('78', 'm1014', 'm10', '0', '评价统计分析', '10-14', '2', '1', 'Order/assess', 'TONGJI', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('80', 'm0201', 'm02', '0', '商城设置', '02-01', '2', '1', 'BaseSetting/timeset', 'BASE', '', 'user');
INSERT INTO `tb_menu` VALUES ('81', 'm0202', 'm02', '0', '积分设置', '02-02', '2', '1', 'BaseSetting/score', 'BASE', '', 'user');
INSERT INTO `tb_menu` VALUES ('82', 'm0203', 'm02', '0', '物流设置', '02-03', '2', '1', 'Warehouse/logistics', 'BASE', '', 'user');
INSERT INTO `tb_menu` VALUES ('84', 'm0301', 'm03', '0', '添加员工', '03-01', '2', '1', 'Admin/add', 'YUANGONG', '', 'user');
INSERT INTO `tb_menu` VALUES ('86', 'm0402', 'm04', '0', '商品分类管理', '04-02', '2', '1', 'Products/category', 'SHANGPIN', '', 'user');
INSERT INTO `tb_menu` VALUES ('87', 'm0405', 'm04', '0', '商品管理', '04-05', '2', '1', 'Products/index', 'SHANGPIN', '', 'user');
INSERT INTO `tb_menu` VALUES ('88', 'm050301', 'm0503', 'm05', '库存盘点', '05-08', '3', '1', 'Invoicing/inventory', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('89', 'm050302', 'm0503', 'm05', '盘点单查询', '05-09', '3', '1', 'Invoicing/inventorylist', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('90', 'm0602', 'm06', '0', '限时特价管理', '06-02', '2', '1', 'Products/sprice', 'CUXIAO', '', 'user');
INSERT INTO `tb_menu` VALUES ('94', 'm0901', 'm09', '0', '会员等级设置', '09-01', '2', '1', 'Users/level', 'HUIYUAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('101', 'm0302', 'm03', '0', '员工管理', '03-02', '2', '1', 'Admin/index', 'YUANGONG', '', 'user');
INSERT INTO `tb_menu` VALUES ('102', 'm0303', 'm03', '0', '权限划分', '03-03', '2', '1', 'Auth/group', 'YUANGONG', '', 'user');
INSERT INTO `tb_menu` VALUES ('104', 'm04', '0', '0', '商品管理', '04', '1', '1', 'SHANGPIN', '', 'fa fa-area-chart', 'user');
INSERT INTO `tb_menu` VALUES ('105', 'm0401', 'm04', '0', '运费模板管理', '04-01', '2', '1', 'Products/yunfei', 'SHANGPIN', '', 'user');
INSERT INTO `tb_menu` VALUES ('107', 'm0404', 'm04', '0', '商品详情添加', '04-04', '2', '1', 'Products/proadd', 'SHANGPIN', '', 'user');
INSERT INTO `tb_menu` VALUES ('108', 'm05', '0', '0', '仓库管理', '05', '1', '1', 'CANGKU', '', 'fa fa-area-chart', 'user');
INSERT INTO `tb_menu` VALUES ('109', 'm0501', 'm05', '0', '入库管理', '05-01', '2', '1', '', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('110', 'm0504', 'm05', '0', '库存统计查询', '05-10', '2', '1', '', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('111', 'm050401', 'm0504', 'm05', '商户库存查询', '05-11', '3', '1', 'Invoicing/index', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('114', 'm1010', 'm10', '0', '运费统计', '10-10', '2', '1', 'Statcenter/yunfei', 'TONGJI', '', 'user');
INSERT INTO `tb_menu` VALUES ('118', 'm07', '0', '0', '订单管理', '07', '1', '1', 'DINGDAN', '', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('119', 'm0206', 'm02', '0', '商户首页设置', '02-06', '2', '1', 'BaseSetting/home', 'BASE', '', 'user');
INSERT INTO `tb_menu` VALUES ('120', 'm0902', 'm09', '0', '会员查询', '09-02', '2', '1', 'Users/member', 'HUIYUAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('123', 'm1102', 'm11', '0', '收银台', '11-02', '2', '1', 'Warehouse/ScanPay', 'SHOUYIN', '', 'user');
INSERT INTO `tb_menu` VALUES ('124', 'm12', '0', '0', '客服管理', '12', '1', '1', 'KEFU', '', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('125', 'm0207', 'm02', '0', '支付配置', '02-07', '2', '1', 'Payset/index', 'BASE', '', 'user');
INSERT INTO `tb_menu` VALUES ('1239', 'm101', '0', '0', '主页', '1', '1', '1', 'Index/Index', 'Index', 'fa fa-home', 'admin');
INSERT INTO `tb_menu` VALUES ('1461', 'm103', '0', '0', '平台管理员管理', '1', '1', '1', '', 'Admin', 'fa fa-user', 'admin');
INSERT INTO `tb_menu` VALUES ('1462', 'm10301', 'm103', '0', '平台管理员设置', '1', '2', '1', 'Admin/index', 'Admin', '', 'admin');
INSERT INTO `tb_menu` VALUES ('2584', 'm1201', 'm12', '0', '客户服务', '12-01', '2', '1', 'Service/index', 'KEFU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2585', 'm13', '0', '0', '商户管理', '13', '1', '1', 'SHANGHU', '', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2586', 'm0701', 'm07', '0', '订单概况', '07-01', '2', '1', 'Order/index', 'DINGDAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2587', 'm0903', 'm09', '0', '会员消费信息', '09-03', '2', '1', 'Users/cons', 'HUIYUAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2589', 'm1301', 'm13', '0', '申请商户', '13-01', '2', '1', 'Storers/register', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2590', 'm1302', 'm13', '0', '商户审核', '13-02', '2', '1', 'Storers/checks', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2595', 'm0907', 'm09', '0', '会员优惠券', '09-07', '2', '1', 'Users/coupons', 'HUIYUAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2596', 'm10', '0', '0', '数据统计', '10', '1', '1', 'TONGJI', '', 'fa fa-area-chart', 'user');
INSERT INTO `tb_menu` VALUES ('2598', 'm050402', 'm0504', 'm05', '供应商库存查询', '05-12', '3', '1', 'Invoicing/supplierList', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('2602', 'm0702', 'm07', '0', '全部订单', '07-02', '2', '1', 'Order/allOrder', 'DINGDAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2604', 'm06', '0', '0', '促销管理', '06', '1', '1', 'CUXIAO', '', 'fa fa-cart-plus', 'user');
INSERT INTO `tb_menu` VALUES ('2605', 'm001', '0', '0', '主页', '01', '1', '1', 'Index/index', 'Index', 'fa fa-home', 'store');
INSERT INTO `tb_menu` VALUES ('2606', 'm002', '0', '0', '商品管理', '02', '1', '1', '', 'Products', 'fa fa-barcode', 'store');
INSERT INTO `tb_menu` VALUES ('2607', 'm00201', 'm002', '0', '商品添加', '02-01', '2', '1', 'Products/proadd', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2608', 'm00202', 'm002', '0', '商品管理', '02-02', '2', '1', 'Products/index', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2611', 'm003', '0', '0', '仓库管理', '03', '1', '1', '', 'Invoicing', 'fa fa-th', 'store');
INSERT INTO `tb_menu` VALUES ('2612', 'm004', '0', '0', '订单管理', '04', '1', '1', '', 'Order', 'fa fa-cart-plus', 'store');
INSERT INTO `tb_menu` VALUES ('2613', 'm00401', 'm004', '0', '订单概况', '04-01', '2', '1', 'Order/index', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2614', 'm00402', 'm004', '0', '全部订单', '04-02', '2', '1', 'Order/allOrder', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2615', 'm006', '0', '0', '员工管理', '06', '1', '1', '', 'Admin', 'fa fa-user-plus', 'store');
INSERT INTO `tb_menu` VALUES ('2616', 'm00601', 'm006', '0', '添加员工', '06-01', '2', '1', 'Admin/add', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2617', 'm00602', 'm006', '0', '员工管理', '06-02', '2', '1', 'Admin/index', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2618', 'm00604', 'm006', '0', '用户组管理', '06-04', '2', '1', 'Auth/group', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2619', 'm007', '0', '0', '数据统计', '07', '1', '1', '', 'Statcenter', 'fa fa-area-chart', 'store');
INSERT INTO `tb_menu` VALUES ('2620', 'm00706', 'm007', '0', '支付核销员管理', '07-06', '2', '1', 'Statcenter/Cancels', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2622', 'm00710', 'm007', '0', '付款方式统计', '07-10', '2', '1', 'Statcenter/PayType', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2630', 'm00301', 'm003', 'm03', '入库单查询', '03-01', '2', '1', 'Invoicing/inwarehouselist', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2631', 'm00302', 'm003', 'm03', '入库单管理', '03-02', '2', '1', 'Invoicing/inwarehouse', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2632', 'm00303', 'm003', 'm03', '出库单查询', '03-03', '2', '1', 'Invoicing/outwarehouselist', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2633', 'm00304', 'm003', 'm03', '出库单管理', '03-04', '2', '1', 'Invoicing/outwarehouse', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2634', 'm00305', 'm003', 'm03', '盘点单查询', '03-05', '2', '1', 'Invoicing/inventorylist', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2635', 'm00306', 'm003', 'm03', '库存盘点', '03-06', '2', '1', 'Invoicing/inventory', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2636', 'm00307', 'm003', 'm03', '库存查询', '03-07', '2', '1', 'Invoicing/index', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2637', 'm00308', 'm003', 'm03', '供应商库存查询', '03-08', '2', '1', 'Invoicing/supplierList', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2638', 'm1303', 'm13', '0', '商户结算', '13-03', '2', '1', 'Storers/storecut', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2639', 'm14', '0', '0', '供货商管理', '14', '1', '1', 'GHS', '', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2640', 'm1401', 'm14', '0', '供货商管理', '14-01', '2', '1', 'Warehouse/suppliers', 'GHS', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2645', 'm0703', 'm07', '0', '积分订单', '07-03', '2', '1', 'Order/allScoreOrder', 'DINGDAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2646', 'm1403', 'm14', '0', '进货单结算', '14-03', '2', '1', 'Invoicing/supplierIn', 'GHS', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2647', 'm1404', 'm14', '0', '供货商结算', '14-04', '2', '1', 'Invoicing/cashover', 'GHS', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2648', 'm050101', 'm0501', 'm05', '入库单管理', '05-02', '3', '1', 'Invoicing/inwarehouse', 'CANGKU', 'fa fa-area-chart', 'user');
INSERT INTO `tb_menu` VALUES ('2649', 'm050102', 'm0501', 'm05', '入库单查询', '05-03', '3', '1', 'Invoicing/inwarehouselist', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('2650', 'm0502', 'm05', 'm05', '出库管理', '05-04', '2', '1', '', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('2651', 'm050201', 'm0502', 'm05', '出库单管理', '05-05', '3', '1', 'Invoicing/outwarehouse', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('2652', 'm050202', 'm0502', 'm05', '出库单查询', '05-06', '3', '1', 'Invoicing/outwarehouselist', 'CANGKU', '', 'user');
INSERT INTO `tb_menu` VALUES ('2653', 'm1012', 'm10', '0', '订单优惠统计', '10-12', '2', '1', 'Statcenter/discountorder', 'TONGJI', '', 'user');
INSERT INTO `tb_menu` VALUES ('2655', 'm1004', 'm10', '0', '付款方式统计', '10-04', '2', '1', 'Statcenter/PayType', 'TONGJI', '', 'user');
INSERT INTO `tb_menu` VALUES ('2656', 'm1013', 'm10', '0', '员工-店铺', '10-13', '2', '1', 'Statcenter/EmpOfStore', 'TONGJI', '', 'user');
INSERT INTO `tb_menu` VALUES ('2657', 'm1402', 'm14', '0', '销售单结算', '14-02', '2', '1', 'Invoicing/supplierSale', 'GHS', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2658', 'm011', '0', '0', '商户管理', '11', '1', '1', '', 'Stores', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2660', 'm1405', 'm14', '0', '结算记录查询', '14-05', '2', '1', 'Invoicing/cashrecord', 'GHS', null, null);
INSERT INTO `tb_menu` VALUES ('2664', 'm0209', 'm02', '0', '主页设置', '02-09', '2', '1', 'BaseSetting/pageset', 'BASE', '', 'user');
INSERT INTO `tb_menu` VALUES ('2665', 'm0406', 'm04', '0', '商品限购设置', '04-04', '2', '1', 'Products/setbuy', 'SHANGPIN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2670', 'm0705', 'm07', '0', '商户订单', '07-02', '2', '1', 'Order/merorder', 'DINGDAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2671', 'm0408', 'm04', '0', '商户商品管理', '04-04', '2', '1', 'Products/merpro', 'SHANGPIN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2672', 'm00212', 'm002', '0', '平台商品选卖', '02-07', '2', '1', 'Products/merpros', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2673', 'm1304', 'm13', '0', '商户销售信息', '13-03', '2', '1', 'Storers/mersales', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2674', 'm01102', 'm011', '0', '银行账户信息', '11-01', '2', '1', 'Stores/sinfoset', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2675', 'm01103', 'm011', '0', '商户信息设置', '11-01', '2', '1', 'Stores/changestinfo', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2676', 'm1305', 'm13', '0', '商户银行账户信息', '13-03', '2', '1', 'Storers/mercards', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2677', 'm00213', 'm002', '0', '限时特价设置', '02-07', '2', '1', 'Products/sprice', '', '', 'store');
INSERT INTO `tb_menu` VALUES ('2678', 'm0909', 'm09', '0', '会员分析', '09-08', '2', '1', 'Users/dowhat', 'HUIYUAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2679', 'm1015', 'm10', '0', '区域-店铺', '10-13', '2', '1', 'Statcenter/EmpOfArea', 'TONGJI', '', 'user');
INSERT INTO `tb_menu` VALUES ('2680', 'm0305', 'm03', '0', ' 区域划分', '03-03', '2', '1', 'Admin/AreaManager', 'YUANGONG', '', 'user');
INSERT INTO `tb_menu` VALUES ('2681', 'm01104', 'm011', '0', '配送员审核', '11-01', '2', '1', 'Store/peisong', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2682', 'm01105', 'm011', '0', '配送订单管理', '11-01', '2', '1', 'Store/Psorder', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2683', 'm01106', 'm011', '0', '配送员提现', '11-01', '2', '1', 'Store/pscheck', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2684', 'm01107', 'm011', '0', '配送费用设置', '11-01', '2', '1', 'Stores/mercutinfo', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2685', 'm01108', 'm011', '0', '账户提现', '11-01', '2', '1', 'Stores/CashManager', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2686', 'm01109', 'm011', '0', '账户金额变动记录', '11-01', '2', '1', 'Stores/storemoney', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2687', 'm1306', 'm13', '0', '商户提现处理', '13-03', '2', '1', 'Storers/cutrecord', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2688', 'm1307', 'm13', '0', '商户扣点设置', '13-03', '2', '1', 'Storers/mercutinfo', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2689', 'm1308', 'm13', '0', '配送提现处理', '13-03', '2', '1', 'Storers/pscheck', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2690', 'm1309', 'm13', '0', '商户账户信息记录', '13-03', '2', '1', 'Storers/MerMoneyinfo', 'SHANGHU', 'fa fa-cogs', 'user');
INSERT INTO `tb_menu` VALUES ('2691', 'm0603', 'm06', '0', '团购设置', '06-02', '2', '1', 'Products/GroupBuy', 'CUXIAO', '', 'user');
INSERT INTO `tb_menu` VALUES ('2692', 'm0604', 'm06', '0', '团购管理', '06-02', '2', '1', 'Products/GroupBuyManager', 'CUXIAO', '', 'user');
INSERT INTO `tb_menu` VALUES ('2693', 'm01110', 'm011', '0', '主页配置', '11-01', '2', '1', 'Store/pageset', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2694', 'm01111', 'm011', '0', '核销员管理', '11-01', '2', '1', 'Stores/cancel', '', 'fa fa-cogs', 'store');
INSERT INTO `tb_menu` VALUES ('2696', 'm0910', 'm09', '0', '提现管理', '09-08', '2', '1', 'Users/getcash', 'HUIYUAN', '', 'user');
INSERT INTO `tb_menu` VALUES ('2697', 'm1017', 'm10', '0', '配送员统计', '10-13', '2', '1', 'Statcenter/Psusers', 'TONGJI', '', 'user');

-- ----------------------------
-- Table structure for tb_menugroup
-- ----------------------------
DROP TABLE IF EXISTS `tb_menugroup`;
CREATE TABLE `tb_menugroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MenuId` varchar(50) NOT NULL COMMENT '菜单ID',
  `GroupId` varchar(20) NOT NULL COMMENT '分组ID',
  `InputId` varchar(20) NOT NULL,
  `InputName` varchar(20) NOT NULL,
  `CreateDate` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1487 DEFAULT CHARSET=utf8 COMMENT='分组权限表';

-- ----------------------------
-- Records of tb_menugroup
-- ----------------------------
INSERT INTO `tb_menugroup` VALUES ('279', 'm001', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('280', 'm002', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('281', 'm00201', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('282', 'm00202', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('283', 'm00212', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('284', 'm00213', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('285', 'm003', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('286', 'm00301', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('287', 'm00302', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('288', 'm00303', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('289', 'm00304', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('290', 'm00305', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('291', 'm00306', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('292', 'm00307', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('293', 'm00308', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('294', 'm004', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('295', 'm00401', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('296', 'm00402', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('297', 'm006', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('298', 'm00601', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('299', 'm00602', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('300', 'm00604', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('301', 'm007', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('302', 'm00706', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('303', 'm00707', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('304', 'm00710', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('305', 'm012', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('306', 'm01201', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('307', 'm01202', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('308', 'm011', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('309', 'm01101', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('310', 'm01102', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('311', 'm01103', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('312', 'm01104', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('313', 'm01105', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('314', 'm01106', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('315', 'm01107', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('316', 'm01108', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('317', 'm01109', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('318', 'm01110', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('319', 'm01111', '9', '10', '13213217648', null);
INSERT INTO `tb_menugroup` VALUES ('707', 'm001', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('708', 'm002', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('709', 'm00201', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('710', 'm00202', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('711', 'm00212', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('712', 'm00213', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('713', 'm003', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('714', 'm00301', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('715', 'm00302', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('716', 'm00303', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('717', 'm00304', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('718', 'm00305', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('719', 'm00306', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('720', 'm00307', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('721', 'm00308', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('722', 'm004', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('723', 'm00401', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('724', 'm00402', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('725', 'm006', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('726', 'm00601', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('727', 'm00602', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('728', 'm00604', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('729', 'm007', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('730', 'm00706', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('731', 'm00710', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('732', 'm012', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('733', 'm01201', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('734', 'm01202', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('735', 'm011', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('736', 'm01102', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('737', 'm01103', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('738', 'm01104', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('739', 'm01105', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('740', 'm01106', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('741', 'm01107', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('742', 'm01108', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('743', 'm01109', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('744', 'm01110', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('745', 'm01111', '13', '16', '18539970943', null);
INSERT INTO `tb_menugroup` VALUES ('746', 'm001', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('747', 'm002', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('748', 'm00201', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('749', 'm00202', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('750', 'm00212', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('751', 'm00213', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('752', 'm003', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('753', 'm00301', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('754', 'm00302', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('755', 'm00303', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('756', 'm00304', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('757', 'm00305', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('758', 'm00306', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('759', 'm00307', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('760', 'm00308', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('761', 'm004', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('762', 'm00401', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('763', 'm00402', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('764', 'm006', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('765', 'm00601', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('766', 'm00602', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('767', 'm00604', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('768', 'm007', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('769', 'm00706', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('770', 'm00710', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('771', 'm012', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('772', 'm01201', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('773', 'm01202', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('774', 'm011', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('775', 'm01102', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('776', 'm01103', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('777', 'm01104', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('778', 'm01105', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('779', 'm01106', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('780', 'm01107', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('781', 'm01108', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('782', 'm01109', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('783', 'm01110', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('784', 'm01111', '10', '11', '13837187135', null);
INSERT INTO `tb_menugroup` VALUES ('1007', 'm001', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1008', 'm002', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1009', 'm00201', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1010', 'm00202', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1011', 'm00212', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1012', 'm00213', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1013', 'm003', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1014', 'm00301', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1015', 'm00302', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1016', 'm00303', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1017', 'm00304', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1018', 'm00305', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1019', 'm00306', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1020', 'm00307', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1021', 'm00308', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1022', 'm004', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1023', 'm00401', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1024', 'm00402', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1025', 'm006', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1026', 'm00601', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1027', 'm00602', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1028', 'm00604', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1029', 'm007', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1030', 'm00706', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1031', 'm00710', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1032', 'm011', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1033', 'm01102', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1034', 'm01103', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1035', 'm01104', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1036', 'm01105', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1037', 'm01106', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1038', 'm01107', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1039', 'm01108', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1040', 'm01109', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1041', 'm01110', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1042', 'm01111', '7', '8', '15238004187', null);
INSERT INTO `tb_menugroup` VALUES ('1043', 'm001', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1044', 'm002', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1045', 'm00201', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1046', 'm00202', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1047', 'm00212', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1048', 'm00213', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1049', 'm003', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1050', 'm00301', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1051', 'm00302', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1052', 'm00303', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1053', 'm00304', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1054', 'm00305', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1055', 'm00306', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1056', 'm00307', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1057', 'm00308', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1058', 'm004', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1059', 'm00401', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1060', 'm00402', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1061', 'm006', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1062', 'm00601', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1063', 'm00602', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1064', 'm00604', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1065', 'm007', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1066', 'm00706', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1067', 'm00710', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1068', 'm011', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1069', 'm01102', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1070', 'm01103', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1071', 'm01104', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1072', 'm01105', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1073', 'm01106', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1074', 'm01107', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1075', 'm01108', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1076', 'm01109', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1077', 'm01110', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1078', 'm01111', '11', '12', '18180572302', null);
INSERT INTO `tb_menugroup` VALUES ('1376', 'm01', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1377', 'm02', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1378', 'm0201', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1379', 'm0202', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1380', 'm0203', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1381', 'm0206', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1382', 'm0207', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1383', 'm0209', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1384', 'm03', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1385', 'm0301', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1386', 'm0302', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1387', 'm0303', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1388', 'm0305', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1389', 'm09', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1390', 'm0901', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1391', 'm0902', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1392', 'm0903', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1393', 'm0907', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1394', 'm0909', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1395', 'm0910', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1396', 'm04', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1397', 'm0402', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1398', 'm0405', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1399', 'm0401', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1400', 'm0404', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1401', 'm0406', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1402', 'm0408', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1403', 'm05', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1404', 'm0503', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1405', 'm050301', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1406', 'm050302', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1407', 'm0501', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1408', 'm050101', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1409', 'm050102', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1410', 'm0504', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1411', 'm050401', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1412', 'm050402', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1413', 'm0502', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1414', 'm050201', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1415', 'm050202', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1416', 'm07', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1417', 'm0701', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1418', 'm0702', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1419', 'm0703', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1420', 'm0705', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1421', 'm12', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1422', 'm1201', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1423', 'm13', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1424', 'm1301', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1425', 'm1302', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1426', 'm1303', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1427', 'm1304', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1428', 'm1305', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1429', 'm1306', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1430', 'm1307', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1431', 'm1308', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1432', 'm1309', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1433', 'm10', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1434', 'm1014', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1435', 'm1010', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1436', 'm1012', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1437', 'm1004', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1438', 'm1013', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1439', 'm1015', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1440', 'm1017', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1441', 'm06', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1442', 'm0601', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1443', 'm0602', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1444', 'm0603', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1445', 'm0604', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1446', 'm14', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1447', 'm1401', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1448', 'm1403', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1449', 'm1404', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1450', 'm1402', '8', '9', 'zhuguiping', null);
INSERT INTO `tb_menugroup` VALUES ('1451', 'm001', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1452', 'm002', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1453', 'm00201', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1454', 'm00202', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1455', 'm00212', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1456', 'm00213', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1457', 'm003', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1458', 'm00301', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1459', 'm00302', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1460', 'm00303', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1461', 'm00304', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1462', 'm00305', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1463', 'm00306', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1464', 'm00307', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1465', 'm00308', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1466', 'm004', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1467', 'm00401', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1468', 'm00402', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1469', 'm006', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1470', 'm00601', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1471', 'm00602', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1472', 'm00604', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1473', 'm007', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1474', 'm00706', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1475', 'm00710', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1476', 'm011', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1477', 'm01102', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1478', 'm01103', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1479', 'm01104', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1480', 'm01105', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1481', 'm01106', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1482', 'm01107', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1483', 'm01108', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1484', 'm01109', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1485', 'm01110', '15', '20', '17711045634', null);
INSERT INTO `tb_menugroup` VALUES ('1486', 'm01111', '15', '20', '17711045634', null);

-- ----------------------------
-- Table structure for tb_merchant
-- ----------------------------
DROP TABLE IF EXISTS `tb_merchant`;
CREATE TABLE `tb_merchant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) NOT NULL COMMENT '商户名称',
  `token` varchar(20) NOT NULL COMMENT '商户标识',
  `userUrl` varchar(50) NOT NULL COMMENT '商户绑定URL',
  `registerTime` int(11) NOT NULL COMMENT '注册时间',
  `storeName` varchar(255) DEFAULT NULL,
  `classDisplay` int(255) DEFAULT NULL,
  `TXtime` int(11) DEFAULT NULL,
  `TKtime` int(11) DEFAULT NULL,
  `sendAddr` varchar(255) DEFAULT NULL COMMENT '商城发货地址(快递单显示)',
  `Cut` float DEFAULT NULL COMMENT '平台商品销售提点',
  `Mcut` float DEFAULT NULL COMMENT '商户商品销售提点',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userName` (`userName`) USING BTREE,
  UNIQUE KEY `token` (`token`) USING BTREE,
  UNIQUE KEY `userUrl` (`userUrl`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='商户表';

-- ----------------------------
-- Records of tb_merchant
-- ----------------------------
INSERT INTO `tb_merchant` VALUES ('5', '15238004187', 'rhbnja145862596121', 'www.huijistore.com', '1458625961', '吃货动起来', '1', '7', '5', null, '2', '2');

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) NOT NULL COMMENT '用户名称',
  `Password` varchar(50) NOT NULL COMMENT '密码',
  `TrueName` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `IsLogin` int(1) DEFAULT NULL COMMENT '是否允许登陆',
  `Sex` enum('0','1','2') DEFAULT '0' COMMENT '性别 0保密 1男 2女',
  `InputId` int(11) NOT NULL,
  `InputName` varchar(50) NOT NULL,
  `HeadImgUrl` varchar(200) DEFAULT 'default.png' COMMENT '头像',
  `Remarks` varchar(200) DEFAULT NULL COMMENT '备注',
  `token` varchar(50) NOT NULL DEFAULT '000000' COMMENT '商户标识',
  `CreateDate` int(11) DEFAULT NULL,
  `LastLoginDate` int(11) DEFAULT NULL,
  `LastUpdateDate` int(11) DEFAULT NULL,
  `IsAdmin` varchar(255) NOT NULL DEFAULT '2' COMMENT '是否为初始管理员1是 2否',
  `DepartmentName` varchar(255) DEFAULT '' COMMENT '所属部门',
  `stoken` varchar(255) DEFAULT '0',
  `AreaIds` varchar(255) DEFAULT NULL COMMENT '管理区域ID',
  `IsLeader` varchar(255) DEFAULT '0' COMMENT '1为领导层',
  `IsServer` varchar(255) DEFAULT '0' COMMENT '是否为客服 0否  1是',
  PRIMARY KEY (`id`,`userName`),
  UNIQUE KEY `EmpolyeeId` (`userName`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of tb_user
-- ----------------------------
INSERT INTO `tb_user` VALUES ('1', 'admin', '8422de09038ed587b8bc5baf3dc5b7cb', 'leaves', '1', '0', '9', 'zhuguiping', 'default.png', null, 'rhbnja145862596121', '1498096771', '1510301449', '1498096771', '2', null, '0', 'a:3:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";}', '1', '1');
INSERT INTO `tb_user` VALUES ('8', '15238004187', '22cf8d98dca2b9de5052ae9253bddef3', '朱桂苹', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1495073384', '1509327738', '1495073384', '1', '1009', 'nPyEo49507333966', null, '0', '0');
INSERT INTO `tb_user` VALUES ('9', 'zhuguiping', '202cb962ac59075b964b07152d234b70', '朱桂苹', '1', '2', '9', 'zhuguiping', 'default.png', null, 'rhbnja145862596121', '1498814987', '1510299622', '1498814987', '2', null, '0', 'a:4:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"5\";}', '1', '1');
INSERT INTO `tb_user` VALUES ('10', '13213217648', 'a6155b0da06d1ad154ad2d039d1fadf4', '郭艳彬', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1495091109', '1498009167', '1495091109', '1', '1010', 'oXTdy49509119968', null, '0', '0');
INSERT INTO `tb_user` VALUES ('11', '13837187135', '604f2c31e67034642b288d76a8df11d5', '李华', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1495348813', '1503906111', '1495348813', '1', '1011', 'hFeGH49534882642', null, '0', '0');
INSERT INTO `tb_user` VALUES ('12', '18180572302', 'ffedf5be3a86e2ee281d54cdc97bc1cf', '唐瑜璠', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1495416009', '1506580876', '1495416009', '1', '1012', 'NAUlV49541606740', null, '0', '0');
INSERT INTO `tb_user` VALUES ('13', '唐瑜璠', '202cb962ac59075b964b07152d234b70', '唐瑜璠', '1', '2', '1', 'admin', 'default.png', null, 'rhbnja145862596121', '1495420147', '1510122171', null, '2', null, '0', 'a:4:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"5\";}', '1', '1');
INSERT INTO `tb_user` VALUES ('14', '肉肉', '202cb962ac59075b964b07152d234b70', '肉肉', '1', '2', '12', '18180572302', 'default.png', null, 'rhbnja145862596121', '1495521581', '1498718836', null, '2', '1012', 'NAUlV49541606740', null, '0', '0');
INSERT INTO `tb_user` VALUES ('15', '13981741088', 'b1563a78ec59337587f6ab6397699afc', '唐延坤', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1495531729', '1498183282', '1495531729', '1', '1014', 'vYfwa49553178058', null, '0', '0');
INSERT INTO `tb_user` VALUES ('16', '18539970943', 'f20b762734a91e8ac057cad2959dd38b', '大炮', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1495618538', '1495618696', '1495618538', '1', '1013', 'QTNFg49561856205', null, '0', '0');
INSERT INTO `tb_user` VALUES ('17', '13408460597', '1a10e97f50db9301315ea45fcef1ba6b', '李俊根', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1495766902', '1504506483', '1495766902', '1', '1015', 'lPduK49576693002', null, '0', '0');
INSERT INTO `tb_user` VALUES ('18', 'leaves', '202cb962ac59075b964b07152d234b70', '程', '1', '0', '9', 'zhuguiping', 'default.png', null, 'rhbnja145862596121', '1497335117', null, '1497335117', '2', null, '0', 'a:4:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"5\";}', '1', '0');
INSERT INTO `tb_user` VALUES ('19', 'lihua', '202cb962ac59075b964b07152d234b70', '李', '1', '0', '9', 'zhuguiping', 'default.png', null, 'rhbnja145862596121', '1497335126', '1504087692', '1497335126', '2', null, '0', 'a:4:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"5\";}', '1', '0');
INSERT INTO `tb_user` VALUES ('20', '17711045634', 'dcac97a552f8472351dad669b34d0507', '王金烽', '1', '', '0', 'store', 'head (42).png', null, 'rhbnja145862596121', '1496721383', '1510278100', '1496721383', '1', '1016', 'Rnmkm49672133165', null, '0', '0');
INSERT INTO `tb_user` VALUES ('21', '13999289304', '05425f51eaeab268c26a5d42f2ccedaf', '徐园园', '1', '', '0', 'store', 'default.png', null, 'rhbnja145862596121', '1496730943', '1499669834', '1496730943', '1', '1017', 'tyivU49673093021', null, '0', '0');
INSERT INTO `tb_user` VALUES ('22', 'duhu', '202cb962ac59075b964b07152d234b70', '杜虎', '1', '1', '1', 'admin', 'default.png', null, 'rhbnja145862596121', '1497837969', '1504591568', null, '2', null, '0', 'a:4:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"5\";}', '1', '1');
INSERT INTO `tb_user` VALUES ('23', 'xue', 'caf1a3dfb505ffed0d024130f58c5cfa', '薛海青', '1', '1', '9', 'zhuguiping', 'default.png', null, 'rhbnja145862596121', '1498819213', null, null, '2', null, '0', 'a:4:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"5\";}', '1', '1');

-- ----------------------------
-- Table structure for tb_usergroup
-- ----------------------------
DROP TABLE IF EXISTS `tb_usergroup`;
CREATE TABLE `tb_usergroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL COMMENT '用户ID',
  `GroupId` int(11) NOT NULL COMMENT '分组ID',
  `InputId` int(11) NOT NULL,
  `InputName` varchar(50) NOT NULL,
  `token` varchar(50) DEFAULT NULL COMMENT '商户标识',
  `CreateDate` int(11) DEFAULT NULL,
  `LastUpdateDate` int(11) DEFAULT NULL,
  `stoken` varchar(255) DEFAULT '0' COMMENT '开点人标识',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='用户分组表';

-- ----------------------------
-- Records of tb_usergroup
-- ----------------------------
INSERT INTO `tb_usergroup` VALUES ('1', '1', '8', '9', 'zhuguiping', 'rhbnja145862596121', null, '1498096771', '0');
INSERT INTO `tb_usergroup` VALUES ('8', '8', '7', '0', 'admin', 'rhbnja145862596121', '1495073384', '1495073384', 'nPyEo49507333966');
INSERT INTO `tb_usergroup` VALUES ('9', '9', '8', '9', 'zhuguiping', 'rhbnja145862596121', null, '1498814987', '0');
INSERT INTO `tb_usergroup` VALUES ('10', '10', '9', '0', 'admin', 'rhbnja145862596121', '1495091109', '1495091109', 'oXTdy49509119968');
INSERT INTO `tb_usergroup` VALUES ('11', '11', '10', '0', 'admin', 'rhbnja145862596121', '1495348813', '1495348813', 'hFeGH49534882642');
INSERT INTO `tb_usergroup` VALUES ('12', '12', '11', '0', 'admin', 'rhbnja145862596121', '1495416009', '1495416009', 'NAUlV49541606740');
INSERT INTO `tb_usergroup` VALUES ('13', '13', '8', '1', 'admin', 'rhbnja145862596121', null, null, '0');
INSERT INTO `tb_usergroup` VALUES ('14', '14', '11', '12', '18180572302', 'rhbnja145862596121', null, null, 'NAUlV49541606740');
INSERT INTO `tb_usergroup` VALUES ('15', '15', '12', '0', 'admin', 'rhbnja145862596121', '1495531729', '1495531729', 'vYfwa49553178058');
INSERT INTO `tb_usergroup` VALUES ('16', '16', '13', '0', 'admin', 'rhbnja145862596121', '1495618538', '1495618538', 'QTNFg49561856205');
INSERT INTO `tb_usergroup` VALUES ('17', '17', '14', '0', 'admin', 'rhbnja145862596121', '1495766902', '1495766902', 'lPduK49576693002');
INSERT INTO `tb_usergroup` VALUES ('18', '18', '8', '9', 'zhuguiping', 'rhbnja145862596121', null, '1497335117', '0');
INSERT INTO `tb_usergroup` VALUES ('19', '19', '8', '9', 'zhuguiping', 'rhbnja145862596121', null, '1497335126', '0');
INSERT INTO `tb_usergroup` VALUES ('20', '20', '15', '0', 'admin', 'rhbnja145862596121', '1496721383', '1496721383', 'Rnmkm49672133165');
INSERT INTO `tb_usergroup` VALUES ('21', '21', '16', '0', 'admin', 'rhbnja145862596121', '1496730943', '1496730943', 'tyivU49673093021');
INSERT INTO `tb_usergroup` VALUES ('22', '22', '8', '1', 'admin', 'rhbnja145862596121', null, null, '0');
INSERT INTO `tb_usergroup` VALUES ('23', '23', '8', '9', 'zhuguiping', 'rhbnja145862596121', null, null, '0');

-- ----------------------------
-- Table structure for tb_wxpayset
-- ----------------------------
DROP TABLE IF EXISTS `tb_wxpayset`;
CREATE TABLE `tb_wxpayset` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(50) NOT NULL,
  `appsecret` varchar(100) NOT NULL,
  `mchid` varchar(20) NOT NULL COMMENT '商户ID',
  `apikey` char(32) NOT NULL COMMENT '支付密匙',
  `apiclient_cert` varchar(200) NOT NULL COMMENT '证书文件',
  `apiclient_key` varchar(200) NOT NULL COMMENT '证书文件',
  `createtime` int(11) DEFAULT NULL,
  `token` varchar(50) NOT NULL COMMENT '商户标识',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `token` (`token`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='微信支付配置表';

-- ----------------------------
-- Records of tb_wxpayset
-- ----------------------------
INSERT INTO `tb_wxpayset` VALUES ('2', 'wxed2f2ef5e18e5423', 'b75711e17f2bcd266923254e85cb4206', '1394550102', 'b1f2e5dd7f38c75aabc96f034aae3d72', 'Upload/cert/rhbnja145862596121/apiclient_cert.pem', 'Upload/cert/rhbnja145862596121/apiclient_key.pem', '1474945859', 'rhbnja145862596121');
