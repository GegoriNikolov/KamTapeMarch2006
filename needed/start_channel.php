<?php 
require "needed/scripts.php";
$inbox = $conn->prepare("SELECT * FROM messages WHERE receiver = ? AND isRead = 0 ORDER BY created DESC");
$inbox->execute([$session['uid']]);
$inbox = $inbox->rowCount();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">



<html>
<head>
	<title> YouTube - Broadcast Yourself. </title>
	<script type="text/javascript" src="/js/AJAX_yts1161839869.js"></script>
	<script type="text/javascript" src="/js/ui_yts1164777409.js"></script>
	<script type="text/javascript" src="/js/components_yts1157352107.js"></script>
        <script type="text/javascript" src="/js/watch_queue_yts1161839869.js"></script>
	
	<link rel="stylesheet" href="/css/styles_yts1164775696.css" type="text/css">
	
	
		
<style type="text/css">

body {
	background-color: #ffffff;
	margin-top: 0px;
}

form { margin: 0px; padding: 0px; }

a:link {
	color: #03C;
	border: none;
	font-family: arial,helvetica,sans-serif;
}

a:visited {
	color: #03C;
	font-family: arial,helvetica,sans-serif;
}

a:hover {
	color: #03C;
	font-family: arial,helvetica,sans-serif;
}

a:active {
	color: #03C;
	font-family: arial,helvetica,sans-serif;
}


a.headerLink:link {
	color: #03C;
	font-family: Arial, Helvetica, sans-serif;
}

a.headerLink:active {
	color: #03C;
	font-family: Arial, Helvetica, sans-serif;
}

a.headerLink:visited {
	color: #03C;
	font-family: Arial, Helvetica, sans-serif;
}

a.titleLink:hover {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

a.titleLink:link {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

a.titleLink:active {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

a.titleLink:visited {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

a.titleLink:hover {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

a.small:link {
	color: #03C;
	font-size: 11px;
}

a.small:active {
	color: #03C;
	font-size: 11px;
}

a.small:visited {
	color: #03C;
	font-size: 11px;
}

a.small:hover {
	color: #03C;
	font-size: 11px;
}


a.masthead:link { 
	color: #03C;
	font-family: Arial, Helvetica, sans-serif;
}

a.masthead:active { 
	color: #03C;
	font-family: Arial, Helvetica, sans-serif;
}

a.masthead:visited { 
	color: #03C;
	font-family: Arial, Helvetica, sans-serif;
}

a.masthead:hover { 
	color: #03C;
	font-family: Arial, Helvetica, sans-serif;
}


a.headers:link { 
	color:#FFFFFF;
}

a.headers:active { 
	color:#FFFFFF;
}

a.headers:visited { 
	color:#FFFFFF;
}

a.headers:hover { 
	color:#FFFFFF;
}

h1,h2,h3,h4,h5,h6 {
	font-weight: bold;
	color: #666666;
	margin-top: 6px;
	margin-bottom: 3px;
	padding: 0px;
	font-family: arial,helvetica,sans-serif;
	}

h1 {
	margin-top: 0px;
	font-size: 13px;
	}

.smallChannelLabel {
	font-weight: bold;
	font-size: 11px;
	color: #333;
}

.pagerNotCurrent { 
	margin-right: 5px;
	cursor: pointer;
	cursor: hand;
	color: #03C;
	text-decoration: underline;
}

.pagerCurrent {
	color: #03C;  
	margin-right: 5px;
	padding: 1px 4px;
	border: 1px solid #999;
	margin-right: 5px;
	background-color: #000000;
}


.mastheadText {
	font-family: Arial, Helvetica, sans-serif;
}

.iconProperties {
	width: 17px;
	height: 17px;
	padding-bottom: 3px;
}

.imageProperties {
	border: 3px solid #666666;
}

#profileImg {
	width: 130px;
	height: 100px;
	margin-right: 3px;
}

#videoImg {
	width: 90px;
	height: 70px;
}

.highlightBoxes {
	background-color: #e6e6e6;
	border: 1px solid #666666;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
	color:#666666;
}


#pBox {
	margin-bottom: 15px;
	padding: 8px 5px;
	border-bottom: 1px solid #666666;
	text-align: left;
}


.pTable {
	width: 100%;
	background-color: #e6e6e6;
	text-align: left;
	border-collapse: collapse;
}



#profileBoxTop {
	width: 300px;
	float: left;
	padding-left: 11px;
	padding-right: 11px;
	padding-top: 8px;
	padding-bottom: 14px;
	border-bottom: none;
	text-align: left;
}

#profileBoxBottom {
	padding-left: 11px;
	padding-right: 11px;
	padding-bottom: 8px;
	border-top: none;	
	margin-bottom: 15px;
	text-align: left;
}

.albumSpacing {
	padding-bottom: 50px;
}

.albumList {
	display: inline; 
	list-style-type: none;
	font-size: 11px;
}

.albumNotEnd {
	width: 40px; 
	height: 40px;
	padding-top: 3px; 
	padding-bottom: 2px; 
	margin-right: 5px;
	float: left;
}

.connectImg {
	width: 55px;
	height: 55px;
	padding-right: 5px;
	padding-left: 5px;
	border: none;
}

.emptyConnectImg {
	width: 35px;
}

.albumArt {
	width: 40px;
	height: 40px;
	border: none;
}


.albumEnd {
	width: 40px; 
	height: 40px;
	padding-top: 3px; 
	padding-bottom: 2px;
	float: left;
}

.extraLeftSpace {
	padding-left: 5px;
}

.profileTopList {
	display: inline; list-style-type: none;
}

.listHorizontal {
	float: left;
}

.honorsImg {
	float: left;
	margin-right: 5px;
	margin-top: 8px;
}

.basicBoxes {
	background-color: #ffffff;
	border: 1px solid #666666;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
	color: #000000;
}

#connectTable {
	width: 100%;
	padding: 8px 5px;
	border-bottom:  1px dashed #666666;
}

#connectBoxTop {
	padding-right: 7px;
	padding-top: 12px;
	font-size: 11px;
}

#connectBoxBottom {
	padding-right: 7px;
	padding-top: 4px;
	padding-bottom: 10px;
	margin-bottom: 15px;
	font-size: 11px;
	height: 32px;
}

.connectList {
	display: inline; list-style-type: none;
}

.linkSection {
	text-align: center;
	border-bottom: 1px solid #666666;
	border-left: 1px solid #666666;
	border-right: 1px solid #666666;
	margin-bottom: 15px;
	padding-top: 3px;
	padding-bottom: 8px;	
	background-color: #ffffff;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
	color: #000000;
}

#channelsBox {
	height: 100px;
	padding-left: 30px;
	padding-top: 8px;
	font-size: 11px;
	margin-bottom: 15px;
	text-align: left;
}

.channelsList {
	display: inline; list-style-type: none;
}

.label {
	font-weight: bold;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
	color: #666666;
}

.labels {
	color: #666666;
}

#labelsSm {
	font-size: 11px;
	padding-top: 3px;
}


#contestsBox {
	padding: 8px 5px;
	margin-bottom: 15px;
	text-align: left;
}

.contestsTableTop {
	padding-top: 5px;
	border-bottom: 0px dashed #666666;
	margin-bottom: 10px;
}

.contestsTableBottom { width: 100%; }

.contestsList {
	margin: 4px;
}


#showBox {
	width: 300px;
	margin-bottom: 15px;
	border-bottom: none;
}

.showTable {
	width: 100%;
	background-color: #e6e6e6;
	text-align: left;
	border-collapse: collapse;
}


tr.showTable td {
	text-align: left;
	padding-left: 8px;
	padding-right: 5px;
	padding-top: 8px;
	padding-bottom: 3px;
}


tr.showTableEnd td {
	text-align: left;
	padding-left: 8px;
	padding-right: 5px;
	padding-bottom: 8px;
	border-bottom: 1px solid #666666;
}


#bulletinBox {
	margin-bottom: 15px;
	text-align: left;
}

.bulletinTable {
	width: 100%;
	text-align: center;
	border-collapse: collapse;
}

tr.bulletinTable th {
	border-bottom: 1px solid;
	padding-top: 2px;
	padding-bottom: 2px;
	border-color: #666666;
	border-right: 1px solid #666666;
}

tr.bulletinTable td {
	padding-left: 5px;
	padding-right: 5px;
	border-bottom: 1px dashed #666666;
	padding-top: 2px;
	padding-bottom: 3px;
	border-color: #666666;
	border-right: 1px solid #666666;
}

tr.bulletinTableNB td {
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 8px; 
	padding-bottom: 8px;
	border-bottom: 1px solid #666666;
	border-color: #666666;
	border-right: 1px solid #666666;
}

#commentsBox { text-align: left; }


.smallText {
	font-size: 11px;
}

.actionsTable {
	margin-top: 3px;
}

tr.actionsTable td {
	padding-right: 3px;
}

.largeTextArea {
	width: 400px; 
	height: 300px; 
	font-family: arial,helvetica,sans-serif; 
	font-size: 12px; 
}

.pageContainerTable {
	border-collapse: collapse;
}

tr.pageContainerTable td {
	text-align: left;
}

#freeFormBox {
	padding: 8px;
	margin-bottom: 15px;
	text-align: left;
	}


.extraVertSpaceSm {
	padding-top: 8px;
}

.extraVertSpaceMini {
	padding-top: 3px;
}

#statsBox {
	margin-top: 8px; 
	text-align: left;
}

.sepBox {
	padding-top: 15px; 
	padding-bottom: 8px;
	text-align: left;
}

.sepBoxReg {
	padding-top: 15px; 
	padding-bottom: 8px;
	text-align: left;
}

.honorsBox {
	padding-top: 5px;
	padding-bottom: 5px;
	font-size: 11px;
}

.scrollersBox {
	height: 122px;
	margin-bottom: 15px;
	padding-top: 5px;
	font-size: 11px;
}

.scrollersBoxTable {
	width: 550px;
	border: none;	
}

tr.scrollersBoxTable td {
	text-align: center;
}

.vloggingBoxes {
	background-color: #ffffff;
	border: 1px solid #ffffff;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
	color: #000000;
}

#vlogBox {
	margin-bottom: 15px;
}

.vlogBoxTable {
	width: 100%;
	border: none;
	text-align: left;
}

tr.vlogBoxTable td {
	border-bottom: none;
	padding-top: 10px;
}

tr.vlogBoxTableEnd td {
	padding-left: 15px;
	padding-bottom: 15px;
	border-bottom: none;
	text-align: right;
}

.postTitles {
	color: #666666;
}
.postText {
	color: #000000;
}

.infoFont {
	color: #666666;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
}

#postMainTitles {
	font-size: 16px;
	font-weight: bold;
	padding-bottom: 10px;
}

#postSubTitles {
	font-size: 12px;
	padding-bottom: 10px;
}

.largeTitles {
	font-size: 14px;
	font-weight: bold;
}

.videoDetailsTable {
	width: 100%;
}

.channelMastheadTable {
	width: 875px;
	padding-top: 4px;
	padding-left: 15px;
	padding-right: 15px;
	margin-bottom: 0px;
	padding-bottom: 0px;
	background-color: #FFFFFF;
	font-size: 11.5px;
	font-family: Arial, Helvetica, sans-serif;
	text-align: left;
}

.paddingSmImg {
	padding-right: 3px;
	
}


.scrollerArrows {
	width: 15px;
	height: 15px;
} 


.headerBox {
	font-family: arial,helvetica,sans-serif;
	padding: 3px 5px;
	border: 1px solid #666666;
	background-color: #666666; 
	font-size: 12px;
	color: #FFFFFF;
}

.headerRCBox { }

.headerRCBox .rch { display: block; }

.headerRCBox .rch * {
	font-family: arial,helvetica,sans-serif;
	display: block;
	height: 1px;
	overflow: hidden;
	background: #666666;
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}

.headerRCBox .rch1 {
	font-family: arial,helvetica,sans-serif;
	border-right: 1px solid #666666;
	padding-right: 1px;
	margin-right: 3px;
	border-left: 1px solid #666666;
	padding-left: 1px;
	margin-left: 3px;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	background: #666666; /* rch5 */
}

.headerRCBox .rch2 {
	font-family: arial,helvetica,sans-serif;
	border-right: 1px solid #666666;
	border-left: 1px solid #666666;
	padding: 0px 1px;
	font-weight: bold;
	font-size: 12px;
	color: #FFFFFF;
	background: #666666; /* rch3 */
	margin: 0px 1px;
}

.headerRCBox .rch3 {
	font-family: arial,helvetica,sans-serif;
	border-right: 1px solid #666666;
	border-left: 1px solid #666666;
	margin: 0px 1px;
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}

.headerRCBox .rch4 {
	
	font-family: arial,helvetica,sans-serif;
	border-right: 1px solid #666666;
	border-left: 1px solid #666666;
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}

.headerRCBox .rch5 {
	font-family: arial,helvetica,sans-serif;
	border-right: 1px solid #666666;
	border-left: 1px solid #666666;
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}

.headerRCBox .content {
	font-family: arial,helvetica,sans-serif;
	background: #666666;
	padding: 0px 6px 2px 6px;
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}


.profileTitleLinks {
	width: 875px;
	font-size: 13px;
	font-family: arial,helvetica,sans-serif;
	margin-bottom: 15px;
	}

#profileSubNav {
	color: #03C;
	text-align: center;
	}
#profileSubNav .delimiter { padding: 0px 6px; }
.profileSubLinks { color:  #03C; }


.highlightBox {
	padding: 9px; 
	background-color: #FFC;
	border: 1px solid #FC3;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
	width: 856px;
	margin-bottom: 15px;
}	

.headerTitle {
	font-size: 14px;
	font-weight: bold;
	font-family: arial,helvetica,sans-serif;	
	text-align: left;
}

.headerTitleEdit {
	font-size: 14px;
	font-weight: bold;
	font-family: arial,helvetica,sans-serif;
	text-align: left;
}
	
.headerTitleRight {
	float: right; 
	padding: 1px 5px 0px 0px;
	font-size: 12px;
	font-weight: bold;
	font-family: arial,helvetica,sans-serif;
}

.headerTitleLite {
	font-size: 13px;
	font-weight: bold;
	font-family: arial,helvetica,sans-serif;
	text-align: left;
}

.flaggingText {
	font-family: arial,helvetica,sans-serif;
	color:  #03C;
	font-size: 12px;
	margin-bottom: 15px;
	text-align: left;
}

.imgBrdr {
	border: 2px solid #666666;
}

.rating {
	margin-top: 3px;
	font-size: 11px;
	height: 14px;
	color: #666666;
	font-family: arial,helvetica,sans-serif; 
}

.vimg {
	width: 130px;
	height: 100px;
	border: 2px solid #666666;
}

.vimg80 {
	width: 80px;
	height: 60px;
	border: 2px solid #666666;
}


.profileSubLinks {
	margin-bottom: 15px;
}

.advertiserBanner {
	width: 875px;
	height: 150px;
	margin-bottom: 15px;
}

.featuredVideo {
	width: 425px;
	height: 350px;
	border: none;
	margin-left: 50px;
	margin-bottom: 15px;
}


/* Bulletin All Code */

#viewBulletinAlert  {
	float: right;
	margin-right: 10px;
	padding: 6px 8px;
	margin-top: 25px;
	border: 1px solid#666666;
	font-weight: bold;
}


#bulletinBoxFull {
	width: 698px;
	margin-bottom: 15px;
	border-right: none;
	border-bottom: none;
}


.bulletinTableFull {
    width: 698px;
	background-color: #e6e6e6;
	text-align: left;
	border-collapse: collapse;
}

tr.bulletinTableFull th {
	border-bottom: 1px solid #666666;
	padding-top: 2px;
	padding-left: 5px;
	padding-bottom: 2px;
	border-right: 1px solid #666666;
}

tr.bulletinTableFull td {
	padding-left: 5px;
	padding-right: 5px;
	border-bottom: 1px dashed #666666;
	padding-top: 5px;
	padding-bottom: 5px;
	border-right: 1px solid #666666;
}


tr.bulletinTableNBFull td {
	padding-left: 5px;
	padding-right: 5px;
	border-bottom: 1px solid #666666;
	padding-top: 5px;
	padding-bottom: 5px;
	border-right: 1px solid #666666;
}

.bulletinImgFull {
	padding-top: 5px;
	padding-bottom: 3px;
	width: 130px;	
}

.bulletinDetailsFull {
	padding-bottom: 5px;
}


/* VLog Code Full */

.vloggingBoxesFull {
	width: 698px;
	background-color: #ffffff;
	border: 1px solid #ffffff;
	font-family: arial,helvetica,sans-serif;
	font-size: 12px;
	color: #000000;
}

#vlogBoxFull {
	width: 698px;
	margin-bottom: 15px;
}

.vlogBoxTableFull {
	width: 100%;
	border: none;
	text-align: left;
}

tr.vlogBoxTableFull td {
	border-bottom: none;
	padding-top: 10px;
}

tr.vlogBoxTableEndFull td {
	padding-left: 15px;
	padding-bottom: 15px;
	border-bottom: none;
	text-align: right;
}

/*Show Code Full */

#showBoxFull {
	width: 698px;
	margin-bottom: 15px;
	border-bottom: none;
}


.showTableFull {
    width: 698px;
	background-color: #e6e6e6;
	text-align: left;
	border-collapse: collapse;
}

tr.showTableFull th {
	border-bottom: 1px solid #666666;
	padding-top: 2px;
	padding-left: 5px;
	padding-bottom: 2px;
	border-right: 1px solid #666666;
}

tr.showTableFull td {
	padding-left: 5px;
	padding-right: 5px;
	border-bottom: 1px dashed #666666;
	padding-top: 5px;
	padding-bottom: 5px;
}

tr.showTableFullTop td {
	padding-left: 5px;
	padding-right: 5px;
	border-bottom: none;
	padding-top: 5px;
	padding-bottom: 5px;
}

tr.showTableNBFull td {
	padding-left: 5px;
	padding-right: 5px;
	border-bottom: 1px solid #666666;
	padding-top: 5px;
	padding-bottom: 5px;
}

.showImgFull {
	padding-top: 5px;
	padding-bottom: 3px;
	width: 130px;	
}

.showDetailsFull {
	padding-bottom: 5px;
}


/* Comments Code Full */

#commentsBoxFull { margin-bottom: 15px; }

.commentsTableFull {
	background-color: #ffffff;
	text-align: left;
}

tr.commentsTableFull th {
	padding: 5px 2px;
	border-bottom: 1px solid #666666;
	border-right: 1px solid #666666;
}

tr.commentsTableFull td {
	padding: 5px;
	border-bottom: 1px dashed #666666;
}


tr.commentsTableNBFull td {
	padding: 5px;
	border-bottom: 1px solid #666666;
}

.commentsImgFull {
	padding-top: 5px;
	padding-bottom: 3px;
	width: 130px;	
}

.commentsDetailsFull {
	padding-bottom: 5px;
}


input.buttonsCustom {
	background-color: #666666;
	font-family: arial,helvetica,sans-serif;
	color: #FFFFFF;
	font-size: 12px;
	padding-top: 5px;
	padding-bottom: 5px;
}


/*Corresponding Pages Layout Code */

#sideContent {
	float: right;
	width: 160px;
}

#mainContent {
	width: 700px;
	margin-right: 10px;
}


.tag_list {
	margin: 1em 0px 0.5em 0px;
	font-weight: bold;
	font-size: 13px;
	color: #666666;
	font-family: arial,helvetica,sans-serif;
}
	
.pointers {
	color: #000000;
}	
	
.vListBox {
	padding: 0px 6px; 
	border: 1px solid #666666;
	background: #e6e6e6;
}

.vEntry {
	padding: 10px 0px;
	border-bottom: 1px dashed #666666;
}

.vDetailEntry {
	clear: left;
	padding: 8px 0px;
	border-bottom: 1px dashed #666666;
}

.vDetailEntry table {
	border-spacing: 0px;
	padding: 0px;
}

.vDetailEntry td { vertical-align: top; }

.vDetailEntry .image { margin-right: 12px; }

.vDetailEntry .tagLabel { float: left; }

.vDetailEntry .tagValue {
	margin-left: 35px;
	margin-bottom: 3px;
}
	
.vDetailEntry .tagTable {
	border-spacing: 0px;
	padding: 0px;
	margin-bottom: 3px;
}

.vDetailEntry .tagTable td {
	font-size: 11px;
	vertical-align: top;
}	

.vtagLabel { float: left; }


.runtime {
	font-size: 11px;
	color: #666666;
	font-family: arial,helvetica,sans-serif;
	font-weight: bold;
}

.title { 
	font-weight: bold;
	font-size: 12px;
	color: #666666;
	font-family: arial,helvetica,sans-serif; 
}

.vtitle {
	font-weight: bold;
	font-size: 12px;
	color: #666666;
	font-family: arial,helvetica,sans-serif;
}	

.vTable {
	border-spacing: 0px;
	padding: 0px;
	}

.vTable td { vertical-align: top; }

.vTable .vinfo { padding-left: 8px; }

.desc {
	margin: 3px 0px;
	font-size: 12px;
	font-family: arial,helvetica,sans-serif; 
	color: #666666;
}

.vdesc {
	margin: 3px 0px;
	font-size: 12px;
	font-family: arial,helvetica,sans-serif;
	color: #666666;
	
}

.subDesc {
	margin: 3px 0px;
	padding-bottom: 3px;
	font-size: 12px;
	font-family: arial,helvetica,sans-serif;
	color: #666666;
}
	
.facets {
	margin-top: 2px;
	margin-bottom: 3px;
	font-size: 11px;
	line-height: 13px;
	font-family: arial,helvetica,sans-serif; 
	color: #666666;
	
}

.vfacets {
	margin-top: 2px;
	margin-bottom: 3px;
	font-size: 11px;
	line-height: 13px;
	font-family: arial,helvetica,sans-serif; 
	color: #666666;
}

.footerBox {
	padding: 0px 5px;
	border: 1px solid #666666; 
	background-color: #666666;  
	margin-bottom: 15px;
	margin-top: 0px;
}

.pagingDiv {
	background: #666666;
	padding: 5px 0px;
	font-size: 13px;
	color: #666666;
	font-weight: bold;
	text-align: right;
	font-family: arial,helvetica,sans-serif;
}

.pagerCurrent {
	color: #666666;
	background-color: #FFFFFF;
	padding: 1px 4px;
	border: 1px solid #666666;
	margin-right: 5px;
	font-family: arial,helvetica,sans-serif;
}

.pagerNotCurrent {
	color: #03C;
	background-color: #e6e6e6;
	padding: 1px 4px;
	border: 1px solid #666666;
	margin-right: 5px;
	cursor: pointer;
	cursor: hand;
	text-decoration: underline;
	font-family: arial,helvetica,sans-serif;
}


/* Inbox & Subscriptions Elements */


#manageNav {
	width: 150px;
	background-color: #e6e6e6; 
	filter:alpha(opacity=25);
	-moz-opacity:.25;opacity:.25;
}

#manageNav ul {
	margin: 0px;
	padding: 0px;
}

#manageNav li {
	list-style: none;
	margin: 0px;
	padding: 5px;
	font-weight: bold;
}

#manageNav .selected { 
	background: #666666; 
}


#manageContent {
	border: 3px solid #666666;
}

/*My Account Dropdown Masthead Styles */

.myAccountContainer {
	position: relative;
	float:right;
}

.myAccountMenu {
	position: absolute;
	top: 0px;
	left: 0px;
	z-index:2;
	width:130px;
	height:120px;
	text-align:left;

}
	
.menuBox {
	margin-top: 15px;
	background-color:#FFF;
	border-top:1px #CCC solid;
	border-left:1px #CCC solid;
	border-right:1px #999 solid;
	border-bottom:1px #999 solid;
	padding:4px;
	width:90px;
}

.menuBoxItem {
	text-align: left;
	padding: 2px;
	margin: 0px;
	background-color: #FFF;
	cursor: pointer;
	cursor: hand;
}

a.dropdownLinks:link {
	color: #03C;
	text-decoration: none;
	}	
a.dropdownLinks:hover {
	color: #03C;
	text-decoration: none;
}
a.dropdownLinks:visited {
	color: #03C;
	text-decoration: none;
}
a.dropdownLinks:active {
	color: #03C;
	text-decoration: none;
}

.searchDiv {
	margin-top: 6px;
	margin-bottom: 4px;
	text-align: right;
	}

/*Empty Set Styles */	
	
.emptySetBox {
	height: 123px;
	padding-bottom: 20px;
	margin-bottom: 15px;
}

.emptySetBoxNoImg {
	padding-bottom: 20px;
	margin-bottom: 15px;	
}

.emptySetTitle {
	margin-left: 25px;
	font-family: arial,helvetica,sans-serif;
	font-size: 20px;
	font-weight:  bold;
	padding-top: 15px;
}

.emptySetContent {
	margin-left: 55px;
	margin-top: 6px;
}

.emptySetContentLg {
	margin-left: 55px;
	margin-top: 6px;
	margin-right: 55px;
}

.emptyImg {	float: right;
	padding-left: 15px;
	border: none;}	
	
</style>

	
	<script type="text/javascript">
		onLoadFunctionList = new Array();
		function performOnLoadFunctions()
		{
			for (var i in onLoadFunctionList)
			{
				onLoadFunctionList[i]();
			}   
		}           
	</script>
</head>

<body onLoad="performOnLoadFunctions();">
<div align="center">
	<table class="channelMastheadTable">
		<tr>
			<td width="104" valign="absmiddle"><a href="/"><img style="border: 0px" src="/img/c_logo_no_text.gif" width="104px" height="37px" /></a></td>			
			<td valign="absmiddle" nowrap>
				<div style="text-align: left; padding-top: 15px; padding-left: 5px;">
					<a href="/index" class="masthead">Home</a> | 
					<a href="/browse?s=mp" class="masthead">Videos</a> | 
					<a href="/members" class="masthead">Channels</a> |
					<a href="/groups_main" class="masthead">Groups</a> | 
				 	<a href="/categories" class="masthead">Categories</a>
				 	| 
				 	<a href="/my_videos_upload" class="masthead">Upload</a>
				</div>
			</td>

<td valign="top" style="text-align: right; font-family: Arial, Helvetica, sans-serif;">			
				<div>
				        <?php if(isset($session)) { ?>
						<strong>Hello, <a href="profile.php?user=<?php echo htmlspecialchars($session['username']) ?>" class="headerLink"><?php echo htmlspecialchars($session['username']) ?></a></strong>&nbsp;<img src="../img/mail<? if($inbox > 0) { echo '_unread'; } ?>.gif" id="mailico" border="0">&nbsp;(<a href="/my_messages.php"><?php echo htmlspecialchars($inbox) ?></a>)
						<? if ($session['staff'] == 1) {?>|
					    <a href="/admin/" class="bold" style="color: #006f09;">ManagerTape</a><? } ?>
						<?php } else if(!isset($session)){ ?>
						<a href="/signup" class="headerLink"><strong>Sign Up</strong></a>
						<?php } ?>
						|
						<a href="/my_account" class="headerLink">My Account</a>
						|
						<a href="/recently_watched" class="headerLink">History</a>
						|
						 <a href="/watch_queue?all" class="headerLink">QuickList</a>
		(<span id="quicklist_numb"><a href="/watch_queue?all" class="headerLink"><script type="text/javascript">var quicklist_count=0;document.write(quicklist_count);</script></a></span>)
|
						<a href="/t/help_center" class="headerLink">Help</a>
						|
						<?php if(isset($session)) { ?>
						<a href="#" class="headerLink" onClick="document.logoutForm.submit()">Log Out</a>
						<?php } else if(!isset($session)){ ?>
						<a href="/signup?next=<?php echo $_SERVER['REQUEST_URI'] ?>" class="headerLink">Log In</a>
						<?php } ?>
				</div>
				<form name="logoutForm" method="post" action="/index" style="margin-bottom: 0px;">
					<input type="hidden" name="action_logout" value="1">
				</form>
				<div class="searchDiv">
					<form name="searchForm" id="searchForm" method="get" action="/results">
		<span class="smallLabel">Search for&nbsp;</span>
		<input tabindex="10000" type="text" name="search_query" maxlength="128" class="searchField" value="">
		&nbsp;
		<input type="submit" name="search" value="Search">
	</form>

				</div>
			</td>		
		</tr>
		<tr>
			<td colspan="3" align="center">
 			
						
		
			

	
							<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 1;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.members/profile;sz=728x90;kch=2354152811;kbg=FFFFFF;kpu=MrCalhoun;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.members/profile;sz=728x90;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.members/profile;sz=728x90;ord=123456789?" width="728" height="90" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		
		
			</td>
		</tr>
	</table><div><img src="/img/masthead_round.gif"></div>
	
<br>
<? if(!empty(invokethConfig("notice"))) { alert(invokethConfig("notice")); } 
if (isset($_COOKIE['hates__dwntime']) && invokethConfig("maintenance") == 1){
    alert("The website is currently in maintenance. Be cool -- some things might break.");
    }
?>