/** Wonderplugin Slider - WordPress Image Video Slider Plugin
 * Copyright 2014 Magic Hills Pty Ltd All Rights Reserved
 * Website: http://www.wonderplugin.com
 * Version 3.8 
 */
(function($) {
    $(document).ready(function() {
        var TRANSITION_EFFECTS = ["fade", "crossfade", "slide", "elastic", "slice", "blinds", "threed", "threedhorizontal", "blocks", "shuffle"];
        var DYNAMIC_POSITIONS = ["topleft", "topright", "bottomleft", "bottomright", "topcenter", "bottomcenter", "centercenter"];
        $(".wonderplugin-engine").css({display: "none"});
        $("#wonderplugin-slider-toolbar").find("li").each(function(index) {
            $(this).click(function() {
                if ($(this).hasClass("wonderplugin-tab-buttons-selected"))
                    return;
                $(this).parent().find("li").removeClass("wonderplugin-tab-buttons-selected");
                if (!$(this).hasClass("laststep"))
                    $(this).addClass("wonderplugin-tab-buttons-selected");
                $("#wonderplugin-slider-tabs").children("li").removeClass("wonderplugin-tab-selected");
                $("#wonderplugin-slider-tabs").children("li").eq(index).addClass("wonderplugin-tab-selected");
                $("#wonderplugin-slider-tabs").removeClass("wonderplugin-tabs-grey");
                if (index == 3) {
                    previewSlider();
                    $("#wonderplugin-slider-tabs").addClass("wonderplugin-tabs-grey")
                } else if (index == 4)
                    publishSlider()
            })
        });
        var getURLParams = function(href) {
            var result =
                    {};
            if (href.indexOf("?") < 0)
                return result;
            var params = href.substring(href.indexOf("?") + 1).split("&");
            for (var i = 0; i < params.length; i++) {
                var value = params[i].split("=");
                if (value && value.length == 2 && value[0].toLowerCase() != "v")
                    result[value[0].toLowerCase()] = value[1]
            }
            return result
        };
        var slideDialog = function(dialogType, onSuccess, data, dataIndex) {
            var dialogTitle = ["image", "video", "Youtube Video", "Vimeo Video"];
            var dialogCode = "<div class='wonderplugin-dialog-container'>" + "<div class='wonderplugin-dialog-bg'></div>" +
                    "<div class='wonderplugin-dialog'>" + "<h3 id='wonderplugin-dialog-title'></h3>" + "<div class='error' id='wonderplugin-dialog-error' style='display:none;'></div>" + "<table id='wonderplugin-dialog-form'>";
            if (dialogType == 2 || dialogType == 3)
                dialogCode += "<tr>" + "<th>Enter video URL</th>" + "<td><input name='wonderplugin-dialog-video' type='text' id='wonderplugin-dialog-video' value='' class='regular-text' /> <input type='button' class='button' id='wonderplugin-dialog-select-video' value='Enter' /></td>" + "</tr>" +
                        "<tr>";
            dialogCode += "<tr>" + "<th>Enter" + (dialogType > 0 ? " poster" : "") + " image URL</th>" + "<td><input name='wonderplugin-dialog-image' type='text' id='wonderplugin-dialog-image' value='' class='regular-text' /> or <input type='button' class='button' data-textid='wonderplugin-dialog-image' id='wonderplugin-dialog-select-image' value='Upload' /></td>" + "</tr>" + "<tr id='wonderplugin-dialog-image-display-tr' style='display:none;'>" + "<th></th>" + "<td><img id='wonderplugin-dialog-image-display' style='width:80px;height:80px;' /></td>" +
                    "</tr>" + "<tr>" + "<th>Thumbnail URL</th>" + "<td><input name='wonderplugin-dialog-thumbnail' type='text' id='wonderplugin-dialog-thumbnail' value='' class='regular-text' /> or <input type='button' class='button' data-textid='wonderplugin-dialog-thumbnail' id='wonderplugin-dialog-select-thumbnail' value='Upload' /></td>" + "</tr>";
            if (dialogType == 1)
                dialogCode += "<tr>" + "<th>MP4 video URL</th>" + "<td><input name='wonderplugin-dialog-mp4' type='text' id='wonderplugin-dialog-mp4' value='' class='regular-text' /> or <input type='button' class='button' data-textid='wonderplugin-dialog-mp4' id='wonderplugin-dialog-select-mp4' value='Upload' /></td>" +
                        "</tr>" + "<tr>" + "<tr>" + "<th>WebM video URL (Optional)</th>" + "<td><input name='wonderplugin-dialog-webm' type='text' id='wonderplugin-dialog-webm' value='' class='regular-text' /> or <input type='button' class='button' data-textid='wonderplugin-dialog-webm' id='wonderplugin-dialog-select-webm' value='Upload' /></td>" + "</tr>" + "<tr>";
            dialogCode += "<tr>" + "<th>Title</th>" + "<td><input name='wonderplugin-dialog-image-title' type='text' id='wonderplugin-dialog-image-title' value='' class='large-text' /></td>" +
                    "</tr>" + "<tr>" + "<th>Description</th>" + "<td><textarea name='wonderplugin-dialog-image-description' type='' id='wonderplugin-dialog-image-description' value='' class='large-text' /></td>" + "</tr>" + "<tr>" + "<th>Button text</th>" + "<td><div style='float:left;'><input name='wonderplugin-dialog-image-button' type='text' id='wonderplugin-dialog-image-button' value='' class='regular-text' style='width:240px;'/> CSS:&nbsp;&nbsp;&nbsp;&nbsp;</div>" + "<div class='select-editable'><select onchange='this.nextElementSibling.value=this.value'>" +
                    "<option value=''></option>" + "<option value='as-btn-blue-small'>as-btn-blue-small</option>" + "<option value='as-btn-blue-medium'>as-btn-blue-medium</option>" + "<option value='as-btn-blue-large'>as-btn-blue-large</option>" + "<option value='as-btn-blueborder-small'>as-btn-blueborder-small</option>" + "<option value='as-btn-blueborder-medium'>as-btn-blueborder-medium</option>" + "<option value='as-btn-blueborder-large'>as-btn-blueborder-large</option>" + "<option value='as-btn-orange-small'>as-btn-orange-small</option>" +
                    "<option value='as-btn-orange-medium'>as-btn-orange-medium</option>" + "<option value='as-btn-orange-large'>as-btn-orange-large</option>" + "<option value='as-btn-orangeborder-small'>as-btn-orangeborder-small</option>" + "<option value='as-btn-orangeborder-medium'>as-btn-orangeborder-medium</option>" + "<option value='as-btn-orangeborder-large'>as-btn-orangeborder-large</option>" + "<option value='as-btn-navy-small'>as-btn-navy-small</option>" + "<option value='as-btn-navy-medium'>as-btn-navy-medium</option>" +
                    "<option value='as-btn-navy-large'>as-btn-navy-large</option>" + "<option value='as-btn-navyborder-small'>as-btn-navyborder-small</option>" + "<option value='as-btn-navyborder-medium'>as-btn-navyborder-medium</option>" + "<option value='as-btn-navyborder-large'>as-btn-navyborder-large</option>" + "<option value='as-btn-white-small'>as-btn-white-small</option>" + "<option value='as-btn-white-medium'>as-btn-white-medium</option>" + "<option value='as-btn-white-large'>as-btn-white-large</option>" + "<option value='as-btn-whiteborder-small'>as-btn-whiteborder-small</option>" +
                    "<option value='as-btn-whiteborder-medium'>as-btn-whiteborder-medium</option>" + "<option value='as-btn-whiteborder-large'>as-btn-whiteborder-large</option>" + "</select><input type='text' name='wonderplugin-dialog-image-buttoncss' id='wonderplugin-dialog-image-buttoncss' value='as-btn-blue-medium' /></div>" + "</td>" + "</tr>" + "<tr>" + "<th>Button link</th>" + "<td><input name='wonderplugin-dialog-image-buttonlink' type='text' id='wonderplugin-dialog-image-buttonlink' value='' class='regular-text' style='width:240px;'/> Target: <input name='wonderplugin-dialog-image-buttonlinktarget' type='text' id='wonderplugin-dialog-image-buttonlinktarget' value='' class='small-text' style='width:120px;' /></td>" +
                    "</tr>";
            dialogCode += "<tr>" + "<th>Click to open Lightbox popup</th>" + "<td><label><input name='wonderplugin-dialog-lightbox' type='checkbox' id='wonderplugin-dialog-lightbox' value='' /> Open current " + dialogTitle[dialogType] + " in Lightbox</label>" + "<br /><label><input name='wonderplugin-dialog-lightbox-size' type='checkbox' id='wonderplugin-dialog-lightbox-size' value='' /> Set Lightbox size </label>" + " <input name='wonderplugin-dialog-lightbox-width' type='text' id='wonderplugin-dialog-lightbox-width' value='960' class='small-text' /> / <input name='wonderplugin-dialog-lightbox-height' type='text' id='wonderplugin-dialog-lightbox-height' value='540' class='small-text' />" +
                    "</td>" + "</tr>";
            if (dialogType == 0)
                dialogCode += "<tr><th>Click to open web link</th>" + "<td>" + "<input name='wonderplugin-dialog-weblink' type='text' id='wonderplugin-dialog-weblink' value='' class='regular-text' />" + "</td>" + "</tr>" + "<tr><th>Set web link target</th>" + "<td>" + "<input name='wonderplugin-dialog-linktarget' type='text' id='wonderplugin-dialog-linktarget' value='' class='regular-text' />" + "</td>" + "</tr>";
            dialogCode += "</table>" + "<br /><br />" + "<div class='wonderplugin-dialog-buttons'>" + "<input type='button' class='button button-primary' id='wonderplugin-dialog-ok' value='OK' />" +
                    "<input type='button' class='button' id='wonderplugin-dialog-cancel' value='Cancel' />" + "</div>" + "</div>" + "</div>";
            var $slideDialog = $(dialogCode);
            $("body").append($slideDialog);
            $(".wonderplugin-dialog").css({"margin-top": String($(document).scrollTop() + 60) + "px"});
            $(".wonderplugin-dialog-bg").css({height: $(document).height() + "px"});
            $("#wonderplugin-dialog-lightbox").click(function() {
                var is_checked = $(this).is(":checked");
                if ($("#wonderplugin-dialog-weblink").length) {
                    $("#wonderplugin-dialog-weblink").attr("disabled",
                            is_checked);
                    if (is_checked)
                        $("#wonderplugin-dialog-weblink").val("")
                }
                if ($("#wonderplugin-dialog-linktarget").length) {
                    $("#wonderplugin-dialog-linktarget").attr("disabled", is_checked);
                    if (is_checked)
                        $("#wonderplugin-dialog-linktarget").val("")
                }
                $("#wonderplugin-dialog-lightbox-width").attr("disabled", !is_checked);
                $("#wonderplugin-dialog-lightbox-height").attr("disabled", !is_checked)
            });
            $(".wonderplugin-dialog").css({"margin-top": String($(document).scrollTop() + 60) + "px"});
            $(".wonderplugin-dialog-bg").css({height: $(document).height() +
                        "px"});
            $("#wonderplugin-dialog-title").html("Add " + dialogTitle[dialogType]);
            if (data) {
                if (dialogType == 2 || dialogType == 3)
                    $("#wonderplugin-dialog-video").val(data.video);
                $("#wonderplugin-dialog-image").val(data.image);
                if (data.image) {
                    $("#wonderplugin-dialog-image-display-tr").css({display: "table-row"});
                    $("#wonderplugin-dialog-image-display").attr("src", data.image)
                }
                $("#wonderplugin-dialog-thumbnail").val(data.thumbnail);
                if ($.trim($("#wonderplugin-dialog-image-title").val()).length <= 0)
                    $("#wonderplugin-dialog-image-title").val(data.title);
                $("#wonderplugin-dialog-image-description").val(data.description);
                $("#wonderplugin-dialog-image-button").val(data.button);
                $("#wonderplugin-dialog-image-buttoncss").val(data.buttoncss);
                $("#wonderplugin-dialog-image-buttonlink").val(data.buttonlink);
                $("#wonderplugin-dialog-image-buttonlinktarget").val(data.buttonlinktarget);
                if (dialogType == 1) {
                    $("#wonderplugin-dialog-mp4").val(data.mp4);
                    $("#wonderplugin-dialog-webm").val(data.webm)
                }
                if (dialogType == 0)
                    if (data.lightbox) {
                        $("#wonderplugin-dialog-weblink").attr("disabled",
                                true);
                        $("#wonderplugin-dialog-linktarget").attr("disabled", true);
                        $("#wonderplugin-dialog-weblink").val("");
                        $("#wonderplugin-dialog-linktarget").val("")
                    } else {
                        $("#wonderplugin-dialog-weblink").val(data.weblink);
                        $("#wonderplugin-dialog-linktarget").val(data.linktarget)
                    }
                $("#wonderplugin-dialog-lightbox").attr("checked", data.lightbox);
                $("#wonderplugin-dialog-lightbox-width").attr("disabled", !data.lightbox);
                $("#wonderplugin-dialog-lightbox-height").attr("disabled", !data.lightbox);
                if ("lightboxsize"in data)
                    $("#wonderplugin-dialog-lightbox-size").attr("checked",
                            data.lightboxsize);
                if (data.lightboxwidth)
                    $("#wonderplugin-dialog-lightbox-width").val(data.lightboxwidth);
                if (data.lightboxheight)
                    $("#wonderplugin-dialog-lightbox-height").val(data.lightboxheight)
            }
            if (dialogType == 2 || dialogType == 3)
                $("#wonderplugin-dialog-select-video").click(function() {
                    var videoData = {type: dialogType, video: $.trim($("#wonderplugin-dialog-video").val()), image: $.trim($("#wonderplugin-dialog-image").val()), thumbnail: $.trim($("#wonderplugin-dialog-thumbnail").val()), title: $.trim($("#wonderplugin-dialog-image-title").val()),
                        description: $.trim($("#wonderplugin-dialog-image-description").val()), button: $.trim($("#wonderplugin-dialog-image-button").val()), buttoncss: $.trim($("#wonderplugin-dialog-image-buttoncss").val()), buttonlink: $.trim($("#wonderplugin-dialog-image-buttonlink").val()), buttonlinktarget: $.trim($("#wonderplugin-dialog-image-buttonlinktarget").val())};
                    $slideDialog.remove();
                    onlineVideoDialog(dialogType, function(items) {
                        items.map(function(data) {
                            wonderplugin_slider_config.slides.push({type: dialogType, image: data.image,
                                thumbnail: data.thumbnail ? data.thumbnail : data.image, video: data.video, mp4: data.mp4, webm: data.webm, title: data.title, description: data.description, button: data.button, buttoncss: data.buttoncss, buttonlink: data.buttonlink, buttonlinktarget: data.buttonlinktarget, weblink: data.weblink, linktarget: data.linktarget, lightbox: data.lightbox, lightboxsize: data.lightboxsize, lightboxwidth: data.lightboxwidth, lightboxheight: data.lightboxheight})
                        });
                        updateMediaTable()
                    }, videoData, true, dataIndex)
                });
            var media_upload_onclick = function(event) {
                event.preventDefault();
                var buttonId = $(this).attr("id");
                var textId = $(this).data("textid");
                var media_uploader = wp.media.frames.file_frame = wp.media({title: "Choose Image", button: {text: "Choose Image"}, multiple: dialogType == 0 && buttonId == "wonderplugin-dialog-select-image"});
                media_uploader.on("select", function(event) {
                    var selection = media_uploader.state().get("selection");
                    if (dialogType == 0 && buttonId == "wonderplugin-dialog-select-image" && selection.length > 1) {
                        var items = [];
                        selection.map(function(attachment) {
                            attachment = attachment.toJSON();
                            if (attachment.type != "image")
                                return;                            
                            var thumbnail;
                            if (attachment.sizes && attachment.sizes.thumbnail && attachment.sizes.thumbnail.url)
                                thumbnail = attachment.sizes.thumbnail.url;
                            else if (attachment.sizes && attachment.sizes.medium && attachment.sizes.medium.url)
                                thumbnail = attachment.sizes.medium.url;
                            else
                                thumbnail = attachment.url;
                            items.push({image: attachment.url, thumbnail: thumbnail, title: attachment.title, description: attachment.description, button: "", buttoncss: "as-btn-blue-medium", buttonlink: "", buttonlinktarget: "",
                                weblink: "", linktarget: "", lightbox: false, lightboxsize: false, lightboxwidth: 960, lightboxheight: 540})
                        });
                        $slideDialog.remove();
                        onSuccess(items)
                    } else {
                        attachment = selection.first().toJSON();                        
                        if (buttonId == "wonderplugin-dialog-select-image") {
                            if (attachment.type != "image") {
                                $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select an image file</p>");
                                return
                            }
                            var thumbnail;
                            if (attachment.sizes && attachment.sizes.thumbnail && attachment.sizes.thumbnail.url)
                                thumbnail = attachment.sizes.thumbnail.url;
                            else if (attachment.sizes &&
                                    attachment.sizes.medium && attachment.sizes.medium.url)
                                thumbnail = attachment.sizes.medium.url;
                            else
                                thumbnail = attachment.url;
                            $("#wonderplugin-dialog-image-display-tr").css({display: "table-row"});
                            $("#wonderplugin-dialog-image-display").attr("src", attachment.url);
                            $("#wonderplugin-dialog-image").val(attachment.url);
                            $("#wonderplugin-dialog-thumbnail").val(thumbnail);
                            if ($.trim($("#wonderplugin-dialog-image-title").val()).length <= 0)
                                $("#wonderplugin-dialog-image-title").val(attachment.title);
                            $("#wonderplugin-dialog-image-description").val(attachment.description)
                        } else if (buttonId ==
                                "wonderplugin-dialog-select-thumbnail") {
                            if (attachment.type != "image") {
                                $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select an image file</p>");
                                return
                            }
                            $("#wonderplugin-dialog-thumbnail").val(attachment.url)
                        } else {
                            if (attachment.type != "video") {
                                $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select a video file</p>");
                                return
                            }
                            $("#" + textId).val(attachment.url)
                        }
                    }
                    $("#wonderplugin-dialog-error").css({display: "none"}).empty()
                });
                media_uploader.open()
            };
            if (parseInt($("#wonderplugin-slider-wp-history-media-uploader").text()) ==
                    1) {
                var buttonId = "";
                var textId = "";
                var history_media_upload_onclick = function(event) {
                    buttonId = $(this).attr("id");
                    textId = $(this).data("textid");
                    var mediaType = buttonId == "wonderplugin-dialog-select-image" || buttonId == "wonderplugin-dialog-select-thumbnail" ? "image" : "video";
                    tb_show("Upload " + mediaType, "media-upload.php?referer=wonderplugin-slider&type=" + mediaType + "&TB_iframe=true", false);
                    return false
                };
                window.send_to_editor = function(html) {
                    tb_remove();
                    if (buttonId == "wonderplugin-dialog-select-image") {
                        var $img =
                                $("img", html);
                        if (!$img.length) {
                            $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select an image file</p>");
                            return
                        }
                        var thumbnail = $img.attr("src");
                        var src = $(html).is("a") ? $(html).attr("href") : thumbnail;
                        $("#wonderplugin-dialog-image-display-tr").css({display: "table-row"});
                        $("#wonderplugin-dialog-image-display").attr("src", thumbnail);
                        $("#wonderplugin-dialog-image").val(src);
                        $("#wonderplugin-dialog-thumbnail").val(thumbnail);
                        if ($.trim($("#wonderplugin-dialog-image-title").val()).length <=
                                0)
                            $("#wonderplugin-dialog-image-title").val($("img", html).attr("title"))
                    } else if (buttonId == "wonderplugin-dialog-select-thumbnail") {
                        var $img = $("img", html);
                        if (!$img.length) {
                            $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select an image file</p>");
                            return
                        }
                        var src = $(html).is("a") ? $(html).attr("href") : $img.attr("src");
                        $("#wonderplugin-dialog-thumbnail").val(src)
                    } else {
                        if ($("img", html).length) {
                            $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select a video file</p>");
                            return
                        }
                        $("#" + textId).val($(html).attr("href"))
                    }
                    $("#wonderplugin-dialog-error").css({display: "none"}).empty()
                };
                $("#wonderplugin-dialog-select-image").click(history_media_upload_onclick);
                $("#wonderplugin-dialog-select-thumbnail").click(history_media_upload_onclick);
                if (dialogType == 1) {
                    $("#wonderplugin-dialog-select-mp4").click(history_media_upload_onclick);
                    $("#wonderplugin-dialog-select-webm").click(history_media_upload_onclick)
                }
            } else {
                $("#wonderplugin-dialog-select-image").click(media_upload_onclick);
                $("#wonderplugin-dialog-select-thumbnail").click(media_upload_onclick);
                if (dialogType == 1) {
                    $("#wonderplugin-dialog-select-mp4").click(media_upload_onclick);
                    $("#wonderplugin-dialog-select-webm").click(media_upload_onclick)
                }
            }
            $("#wonderplugin-dialog-ok").click(function() {
                if ($.trim($("#wonderplugin-dialog-image").val()).length <= 0) {
                    $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select an image file</p>");
                    return
                }
                if (dialogType == 1 && $.trim($("#wonderplugin-dialog-mp4").val()).length <=
                        0) {
                    $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please select a video file</p>");
                    return
                }
                var item = {image: $.trim($("#wonderplugin-dialog-image").val()), thumbnail: $.trim($("#wonderplugin-dialog-thumbnail").val()), video: $.trim($("#wonderplugin-dialog-video").val()), mp4: $.trim($("#wonderplugin-dialog-mp4").val()), webm: $.trim($("#wonderplugin-dialog-webm").val()), title: $.trim($("#wonderplugin-dialog-image-title").val()), description: $.trim($("#wonderplugin-dialog-image-description").val()),
                    button: $.trim($("#wonderplugin-dialog-image-button").val()), buttoncss: $.trim($("#wonderplugin-dialog-image-buttoncss").val()), buttonlink: $.trim($("#wonderplugin-dialog-image-buttonlink").val()), buttonlinktarget: $.trim($("#wonderplugin-dialog-image-buttonlinktarget").val()), weblink: $.trim($("#wonderplugin-dialog-weblink").val()), linktarget: $.trim($("#wonderplugin-dialog-linktarget").val()), lightbox: $("#wonderplugin-dialog-lightbox").is(":checked"), lightboxsize: $("#wonderplugin-dialog-lightbox-size").is(":checked"),
                    lightboxwidth: parseInt($.trim($("#wonderplugin-dialog-lightbox-width").val())), lightboxheight: parseInt($.trim($("#wonderplugin-dialog-lightbox-height").val()))};
                $slideDialog.remove();
                onSuccess([item])
            });
            $("#wonderplugin-dialog-cancel").click(function() {
                $slideDialog.remove()
            })
        };
        var onlineVideoDialog = function(dialogType, onSuccess, videoData, invokeFromSlideDialog, dataIndex) {
            var dialogTitle = ["Image", "Video", "Youtube Video", "Vimeo Video"];
            var dialogExample = ["", "", "https://www.youtube.com/watch?v=wswxQ3mhwqQ",
                "http://vimeo.com/1084537"];
            var dialogCode = "<div class='wonderplugin-dialog-container'>" + "<div class='wonderplugin-dialog-bg'></div>" + "<div class='wonderplugin-dialog'>" + "<h3 id='wonderplugin-dialog-title'></h3>" + "<div class='error' id='wonderplugin-dialog-error' style='display:none;'></div>" + "<table id='wonderplugin-dialog-form'>" + "<tr>" + "<th>Enter " + dialogTitle[dialogType] + " URL</th>" + "<td><input name='wonderplugin-dialog-video' type='text' id='wonderplugin-dialog-video' value='' class='regular-text' />" +
                    "<p>URL Example: " + dialogExample[dialogType] + "<p>" + "</td>" + "</tr>";
            dialogCode += "</table>" + "<div id='wonderplugin-slider-video-dialog-loading'></div>" + "<div class='wonderplugin-dialog-buttons'>" + "<input type='button' class='button button-primary' id='wonderplugin-dialog-ok' value='OK' />" + "<input type='button' class='button' id='wonderplugin-dialog-cancel' value='Cancel' />" + "</div>" + "</div>" + "</div>";
            var $videoDialog = $(dialogCode);
            $("body").append($videoDialog);
            $(".wonderplugin-dialog").css({"margin-top": String($(document).scrollTop() +
                        60) + "px"});
            $(".wonderplugin-dialog-bg").css({height: $(document).height() + "px"});
            if (!videoData)
                videoData = {type: dialogType};
            $("#wonderplugin-dialog-title").html("Add " + dialogTitle[dialogType]);
            var videoDataReturn = function() {
                $videoDialog.remove();
                slideDialog(dialogType, function(items) {
                    if (items && items.length > 0) {
                        if (typeof dataIndex !== "undefined" && dataIndex >= 0)
                            wonderplugin_slider_config.slides.splice(dataIndex, 1);
                        items.map(function(data) {
                            var result = {type: dialogType, image: data.image, thumbnail: data.thumbnail ?
                                        data.thumbnail : data.image, video: data.video, mp4: data.mp4, webm: data.webm, title: data.title, description: data.description, button: data.button, buttoncss: data.buttoncss, buttonlink: data.buttonlink, buttonlinktarget: data.buttonlinktarget, weblink: data.weblink, linktarget: data.linktarget, lightbox: data.lightbox, lightboxsize: data.lightboxsize, lightboxwidth: data.lightboxwidth, lightboxheight: data.lightboxheight};
                            if (typeof dataIndex !== "undefined" && dataIndex >= 0)
                                wonderplugin_slider_config.slides.splice(dataIndex, 0, result);
                            else
                                wonderplugin_slider_config.slides.push(result)
                        });
                        updateMediaTable()
                    }
                }, videoData, dataIndex)
            };
            $("#wonderplugin-dialog-ok").click(function() {
                var href = $.trim($("#wonderplugin-dialog-video").val());
                if (href.length <= 0) {
                    $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please enter a " + dialogTitle[dialogType] + " URL</p>");
                    return
                }
                if (dialogType == 2) {
                    var youtubeId = "";
                    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
                    var match = href.match(regExp);
                    if (match &&
                            match[7] && match[7].length == 11)
                        youtubeId = match[7];
                    else {
                        $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please enter a valid Youtube URL</p>");
                        return
                    }
                    var result = "http://www.youtube.com/embed/" + youtubeId;
                    var params = getURLParams(href);
                    var first = true;
                    for (var key in params) {
                        if (first) {
                            result += "?";
                            first = false
                        } else
                            result += "&";
                        result += key + "=" + params[key]
                    }
                    videoData.video = result;
                    videoData.image = "http://img.youtube.com/vi/" + youtubeId + "/0.jpg";
                    videoData.thumbnail = "http://img.youtube.com/vi/" + youtubeId +
                            "/1.jpg";
                    videoDataReturn()
                } else if (dialogType == 3) {
                    var vimeoId = "";
                    var regExp = /^.*(vimeo\.com\/)((video\/)|(channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
                    var match = href.match(regExp);
                    if (match && match[6])
                        vimeoId = match[6];
                    else {
                        $("#wonderplugin-dialog-error").css({display: "block"}).html("<p>Please enter a valid Vimeo URL</p>");
                        return
                    }
                    var result = "http://player.vimeo.com/video/" + vimeoId;
                    var params = getURLParams(href);
                    var first = true;
                    for (var key in params) {
                        if (first) {
                            result += "?";
                            first = false
                        } else
                            result +=
                                    "&";
                        result += key + "=" + params[key]
                    }
                    videoData.video = result;
                    $("#wonderplugin-slider-video-dialog-loading").css({display: "block"});
                    $.ajax({url: "http://www.vimeo.com/api/v2/video/" + vimeoId + ".json?callback=?", dataType: "json", timeout: 3E3, data: {format: "json"}, success: function(data) {
                            videoData.image = data[0].thumbnail_large;
                            videoData.thumbnail = data[0].thumbnail_medium;
                            videoDataReturn()
                        }, error: function() {
                            videoDataReturn()
                        }})
                }
            });
            $("#wonderplugin-dialog-cancel").click(function() {
                $videoDialog.remove();
                if (invokeFromSlideDialog)
                    videoDataReturn()
            })
        };
        var updateMediaTable = function() {
            var mediaType = ["Image", "Video", "YouTube", "Vimeo"];
            $("#wonderplugin-slider-media-table").empty();
            for (var i = 0; i < wonderplugin_slider_config.slides.length; i++)
                $("#wonderplugin-slider-media-table").append("<li>" + "<div class='wonderplugin-slider-media-table-id'>" + (i + 1) + "</div>" + "<div class='wonderplugin-slider-media-table-img'>" + "<img class='wonderplugin-slider-media-table-image' data-order='" + i + "' src='" + wonderplugin_slider_config.slides[i].thumbnail + "' />" + "</div>" + "<div class='wonderplugin-slider-media-table-type'>" +
                        mediaType[wonderplugin_slider_config.slides[i].type] + "</div>" + "<div class='wonderplugin-slider-media-table-buttons-edit'>" + "<a class='wonderplugin-slider-media-table-button wonderplugin-slider-media-table-edit'>Edit</a>&nbsp;|&nbsp;" + "<a class='wonderplugin-slider-media-table-button wonderplugin-slider-media-table-delete'>Delete</a>" + "</div>" + "<div class='wonderplugin-slider-media-table-buttons-move'>" + "<a class='wonderplugin-slider-media-table-button wonderplugin-slider-media-table-moveup'>Move Up</a>&nbsp;|&nbsp;" +
                        "<a class='wonderplugin-slider-media-table-button wonderplugin-slider-media-table-movedown'>Move Down</a>" + "</div>" + "<div style='clear:both;'></div>" + "</li>");
            $(".wonderplugin-slider-media-table-image").wpdraggable(wonderPluginMediaTableMove)
        };
        $("#wonderplugin-add-image").click(function() {
            slideDialog(0, function(items) {
                items.map(function(data) {
                    wonderplugin_slider_config.slides.push({type: 0, image: data.image, thumbnail: data.thumbnail ? data.thumbnail : data.image, video: data.video, mp4: data.mp4, webm: data.webm,
                        title: data.title, description: data.description, button: data.button, buttoncss: data.buttoncss, buttonlink: data.buttonlink, buttonlinktarget: data.buttonlinktarget, weblink: data.weblink, linktarget: data.linktarget, lightbox: data.lightbox, lightboxsize: data.lightboxsize, lightboxwidth: data.lightboxwidth, lightboxheight: data.lightboxheight})
                });
                updateMediaTable()
            })
        });
        $("#wonderplugin-add-video").click(function() {
            slideDialog(1, function(items) {
                items.map(function(data) {
                    wonderplugin_slider_config.slides.push({type: 1,
                        image: data.image, thumbnail: data.thumbnail ? data.thumbnail : data.image, video: data.video, mp4: data.mp4, webm: data.webm, title: data.title, description: data.description, button: data.button, buttoncss: data.buttoncss, buttonlink: data.buttonlink, buttonlinktarget: data.buttonlinktarget, weblink: data.weblink, linktarget: data.linktarget, lightbox: data.lightbox, lightboxsize: data.lightboxsize, lightboxwidth: data.lightboxwidth, lightboxheight: data.lightboxheight})
                });
                updateMediaTable()
            })
        });
        $("#wonderplugin-add-youtube").click(function() {
            onlineVideoDialog(2,
                    function(items) {
                        items.map(function(data) {
                            wonderplugin_slider_config.slides.push({type: 2, image: data.image, thumbnail: data.thumbnail ? data.thumbnail : data.image, video: data.video, mp4: data.mp4, webm: data.webm, title: data.title, description: data.description, button: data.button, buttoncss: data.buttoncss, buttonlink: data.buttonlink, buttonlinktarget: data.buttonlinktarget, weblink: data.weblink, linktarget: data.linktarget, lightbox: data.lightbox, lightboxsize: data.lightboxsize, lightboxwidth: data.lightboxwidth, lightboxheight: data.lightboxheight})
                        });
                        updateMediaTable()
                    })
        });
        $("#wonderplugin-add-vimeo").click(function() {
            onlineVideoDialog(3, function(items) {
                items.map(function(data) {
                    wonderplugin_slider_config.slides.push({type: 2, image: data.image, thumbnail: data.thumbnail ? data.thumbnail : data.image, video: data.video, mp4: data.mp4, webm: data.webm, title: data.title, description: data.description, button: data.button, buttoncss: data.buttoncss, buttonlink: data.buttonlink, buttonlinktarget: data.buttonlinktarget, weblink: data.weblink, linktarget: data.linktarget, lightbox: data.lightbox,
                        lightboxsize: data.lightboxsize, lightboxwidth: data.lightboxwidth, lightboxheight: data.lightboxheight})
                });
                updateMediaTable()
            })
        });
        $(document).on("click", ".wonderplugin-slider-media-table-edit", function() {
            var index = $(this).parent().parent().index();
            var mediaType = wonderplugin_slider_config.slides[index].type;
            slideDialog(mediaType, function(items) {
                if (items && items.length > 0) {
                    wonderplugin_slider_config.slides.splice(index, 1);
                    items.map(function(data) {
                        wonderplugin_slider_config.slides.splice(index, 0, {type: mediaType,
                            image: data.image, thumbnail: data.thumbnail ? data.thumbnail : data.image, video: data.video, mp4: data.mp4, webm: data.webm, title: data.title, description: data.description, button: data.button, buttoncss: data.buttoncss, buttonlink: data.buttonlink, buttonlinktarget: data.buttonlinktarget, weblink: data.weblink, linktarget: data.linktarget, lightbox: data.lightbox, lightboxsize: data.lightboxsize, lightboxwidth: data.lightboxwidth, lightboxheight: data.lightboxheight})
                    });
                    updateMediaTable()
                }
            }, wonderplugin_slider_config.slides[index],
                    index)
        });
        $(document).on("click", ".wonderplugin-slider-media-table-delete", function() {
            var $tr = $(this).parent().parent();
            var index = $tr.index();
            wonderplugin_slider_config.slides.splice(index, 1);
            $tr.remove();
            $("#wonderplugin-slider-media-table").find("li").each(function(index) {
                $(this).find(".wonderplugin-slider-media-table-id").text(index + 1);
                $(this).find("img").data("order", index);
                $(this).find("img").css({top: 0, left: 0})
            })
        });
        var wonderPluginMediaTableMove = function(i, j) {
            var len = wonderplugin_slider_config.slides.length;
            if (j < 0)
                j = 0;
            if (j > len - 1)
                j = len - 1;
            if (i == j) {
                $("#wonderplugin-slider-media-table").find("li").eq(i).find("img").css({top: 0, left: 0});
                return
            }
            var $tr = $("#wonderplugin-slider-media-table").find("li").eq(i);
            var data = wonderplugin_slider_config.slides[i];
            wonderplugin_slider_config.slides.splice(i, 1);
            wonderplugin_slider_config.slides.splice(j, 0, data);
            var $trj = $("#wonderplugin-slider-media-table").find("li").eq(j);
            $tr.remove();
            if (j > i)
                $trj.after($tr);
            else
                $trj.before($tr);
            $("#wonderplugin-slider-media-table").find("li").each(function(index) {
                $(this).find(".wonderplugin-slider-media-table-id").text(index +
                        1);
                $(this).find("img").data("order", index);
                $(this).find("img").css({top: 0, left: 0})
            });
            $tr.find("img").wpdraggable(wonderPluginMediaTableMove)
        };
        $(document).on("click", ".wonderplugin-slider-media-table-moveup", function() {
            var $tr = $(this).parent().parent();
            var index = $tr.index();
            var data = wonderplugin_slider_config.slides[index];
            wonderplugin_slider_config.slides.splice(index, 1);
            if (index == 0) {
                wonderplugin_slider_config.slides.push(data);
                var $last = $tr.parent().find("li:last");
                $tr.remove();
                $last.after($tr)
            } else {
                wonderplugin_slider_config.slides.splice(index -
                        1, 0, data);
                var $prev = $tr.prev();
                $tr.remove();
                $prev.before($tr)
            }
            $("#wonderplugin-slider-media-table").find("li").each(function(index) {
                $(this).find(".wonderplugin-slider-media-table-id").text(index + 1);
                $(this).find("img").data("order", index);
                $(this).find("img").css({top: 0, left: 0})
            });
            $tr.find("img").wpdraggable(wonderPluginMediaTableMove)
        });
        $(document).on("click", ".wonderplugin-slider-media-table-movedown", function() {
            var $tr = $(this).parent().parent();
            var index = $tr.index();
            var len = wonderplugin_slider_config.slides.length;
            var data = wonderplugin_slider_config.slides[index];
            wonderplugin_slider_config.slides.splice(index, 1);
            if (index == len - 1) {
                wonderplugin_slider_config.slides.unshift(data);
                var $first = $tr.parent().find("li:first");
                $tr.remove();
                $first.before($tr)
            } else {
                wonderplugin_slider_config.slides.splice(index + 1, 0, data);
                var $next = $tr.next();
                $tr.remove();
                $next.after($tr)
            }
            $("#wonderplugin-slider-media-table").find("li").each(function(index) {
                $(this).find(".wonderplugin-slider-media-table-id").text(index + 1);
                $(this).find("img").data("order",
                        index);
                $(this).find("img").css({top: 0, left: 0})
            });
            $tr.find("img").wpdraggable(wonderPluginMediaTableMove)
        });
        var configSkinOptions = ["showbottomshadow", "navshowpreview", "border", "autoplay", "randomplay", "loadimageondemand", "autoplayvideo", "isresponsive", "showtext", "arrowstyle", "showtimer", "loop", "slideinterval", "arrowimage", "arrowwidth", "arrowheight", "arrowtop", "arrowmargin", "navstyle", "navimage", "navwidth", "navheight", "navspacing", "navmarginx", "navmarginy", "playvideoimage", "playvideoimagewidth", "playvideoimageheight",
            "textformat"];
        var configTextOptions = ["textcss", "textbgcss", "titlecss", "descriptioncss", "textpositionstatic", "textpositiondynamic", "textautohide"];
        var defaultSkinOptions = {};
        for (var key in WONDERPLUGIN_SLIDER_SKIN_OPTIONS) {
            defaultSkinOptions[key] = {};
            for (var i = 0; i < configSkinOptions.length; i++)
                defaultSkinOptions[key][configSkinOptions[i]] = WONDERPLUGIN_SLIDER_SKIN_OPTIONS[key][configSkinOptions[i]];
            defaultSkinOptions[key]["scalemode"] = "fill";
            defaultSkinOptions[key]["arrowimagemode"] = "defined";
            defaultSkinOptions[key]["navimagemode"] =
                    "defined";
            defaultSkinOptions[key]["fullwidth"] = false;
            defaultSkinOptions[key]["isfullscreen"] = false;
            defaultSkinOptions[key]["paddingleft"] = 0;
            if (key == "vertical")
                defaultSkinOptions[key]["paddingright"] = 72;
            else if (key == "rightthumbs")
                defaultSkinOptions[key]["paddingright"] = 140;
            else if (key == "featurelist" || key == "righttabs" || key == "righttabsdark")
                defaultSkinOptions[key]["paddingright"] = 240;
            else if (key == "verticalnumber")
                defaultSkinOptions[key]["paddingright"] = 48;
            else
                defaultSkinOptions[key]["paddingright"] =
                        0;
            defaultSkinOptions[key]["paddingtop"] = 0;
            if (key == "topcarousel")
                defaultSkinOptions[key]["paddingtop"] = 84;
            defaultSkinOptions[key]["paddingbottom"] = 0;
            for (var i = 0; i < configTextOptions.length; i++)
                if (defaultSkinOptions[key]["textformat"]in WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS)
                    defaultSkinOptions[key][configTextOptions[i]] = WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[defaultSkinOptions[key]["textformat"]][configTextOptions[i]]
        }
        var printSkinOptions = function(options) {
            $("#wonderplugin-slider-showbottomshadow").attr("checked",
                    options.showbottomshadow);
            $("#wonderplugin-slider-navshowpreview").attr("checked", options.navshowpreview);
            $("#wonderplugin-slider-border").val(options.border);
            $("#wonderplugin-slider-paddingleft").val(options.paddingleft);
            $("#wonderplugin-slider-paddingright").val(options.paddingright);
            $("#wonderplugin-slider-paddingtop").val(options.paddingtop);
            $("#wonderplugin-slider-paddingbottom").val(options.paddingbottom);
            $("input:radio[name=wonderplugin-slider-arrowimagemode][value=" + options.arrowimagemode +
                    "]").attr("checked", true);
            if (wonderplugin_slider_config.arrowimagemode == "custom") {
                $("#wonderplugin-slider-customarrowimage").val(options.arrowimage);
                $("#wonderplugin-slider-displayarrowimage").attr("src", options.arrowimage)
            } else {
                $("#wonderplugin-slider-arrowimage").val(options.arrowimage);
                $("#wonderplugin-slider-displayarrowimage").attr("src", $("#wonderplugin-slider-jsfolder").text() + options.arrowimage)
            }
            $("#wonderplugin-slider-arrowstyle").val(options.arrowstyle);
            $("#wonderplugin-slider-arrowwidth").val(options.arrowwidth);
            $("#wonderplugin-slider-arrowheight").val(options.arrowheight);
            $("#wonderplugin-slider-arrowtop").val(options.arrowtop);
            $("#wonderplugin-slider-arrowmargin").val(options.arrowmargin);
            if (options.navstyle != "bullets")
                $("#wonderplugin-slider-confignavimage").hide();
            else {
                $("#wonderplugin-slider-confignavimage").show();
                $("input:radio[name=wonderplugin-slider-navimagemode][value=" + options.navimagemode + "]").attr("checked", true);
                if (wonderplugin_slider_config.navimagemode == "custom") {
                    $("#wonderplugin-slider-customnavimage").val(options.navimage);
                    $("#wonderplugin-slider-displaynavimage").attr("src", options.navimage)
                } else {
                    $("#wonderplugin-slider-navimage").val(options.navimage);
                    $("#wonderplugin-slider-displaynavimage").attr("src", $("#wonderplugin-slider-jsfolder").text() + options.navimage)
                }
                $("#wonderplugin-slider-navwidth").val(options.navwidth);
                $("#wonderplugin-slider-navheight").val(options.navheight);
                $("#wonderplugin-slider-navspacing").val(options.navspacing);
                $("#wonderplugin-slider-navmarginx").val(options.navmarginx);
                $("#wonderplugin-slider-navmarginy").val(options.navmarginy)
            }
            $("#wonderplugin-slider-playvideoimage").val(options.playvideoimage);
            $("#wonderplugin-slider-displayplayvideoimage").attr("src", $("#wonderplugin-slider-jsfolder").text() + options.playvideoimage);
            $("#wonderplugin-slider-playvideoimagewidth").val(options.playvideoimagewidth);
            $("#wonderplugin-slider-playvideoimageheight").val(options.playvideoimageheight);
            $("#wonderplugin-slider-textformat").val(options.textformat);
            $("#wonderplugin-slider-textcss").val(options.textcss);
            $("#wonderplugin-slider-textbgcss").val(options.textbgcss);
            $("#wonderplugin-slider-titlecss").val(options.titlecss);
            $("#wonderplugin-slider-descriptioncss").val(options.descriptioncss);
            $("#wonderplugin-slider-textpositionstatic").val(options.textpositionstatic);
            var positions = options.textpositiondynamic.split(",");
            for (var i = 0; i < DYNAMIC_POSITIONS.length; i++)
                $("#wonderplugin-slider-textpositiondynamic-" + DYNAMIC_POSITIONS[i]).attr("checked", positions.indexOf(DYNAMIC_POSITIONS[i]) > -1);
            if (WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[options.textformat]["textstyle"] == "static") {
                $(".wonderplugin-slider-texteffect-static").css({display: "block"});
                $(".wonderplugin-slider-texteffect-dynamic").css({display: "none"})
            } else if (WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[options.textformat]["textstyle"] == "dynamic") {
                $(".wonderplugin-slider-texteffect-static").css({display: "none"});
                $(".wonderplugin-slider-texteffect-dynamic").css({display: "block"})
            }
            $("#wonderplugin-slider-textautohide").attr("checked", options.textautohide)
        };
        $("input:radio[name=wonderplugin-slider-skin]").click(function() {
            if ($(this).val() == wonderplugin_slider_config.skin)
                return;
            $(".wonderplugin-tab-skin").find("img").removeClass("selected");
            $("input:radio[name=wonderplugin-slider-skin]:checked").parent().find("img").addClass("selected");
            wonderplugin_slider_config.skin = $(this).val();
            printSkinOptions(defaultSkinOptions[$(this).val()])
        });
        $(".wonderplugin-slider-options-menu-item").each(function(index) {
            $(this).click(function() {
                if ($(this).hasClass("wonderplugin-slider-options-menu-item-selected"))
                    return;
                $(".wonderplugin-slider-options-menu-item").removeClass("wonderplugin-slider-options-menu-item-selected");
                $(this).addClass("wonderplugin-slider-options-menu-item-selected");
                $(".wonderplugin-slider-options-tab").removeClass("wonderplugin-slider-options-tab-selected");
                $(".wonderplugin-slider-options-tab").eq(index).addClass("wonderplugin-slider-options-tab-selected")
            })
        });
        var updateSliderOptions = function() {
            wonderplugin_slider_config.name = $.trim($("#wonderplugin-slider-name").val());
            wonderplugin_slider_config.width = parseInt($.trim($("#wonderplugin-slider-width").val()));
            wonderplugin_slider_config.height = parseInt($.trim($("#wonderplugin-slider-height").val()));
            wonderplugin_slider_config.skin =
                    $("input:radio[name=wonderplugin-slider-skin]:checked").val();
            wonderplugin_slider_config.autoplay = $("#wonderplugin-slider-autoplay").is(":checked");
            wonderplugin_slider_config.randomplay = $("#wonderplugin-slider-randomplay").is(":checked");
            wonderplugin_slider_config.loadimageondemand = $("#wonderplugin-slider-loadimageondemand").is(":checked");
            wonderplugin_slider_config.autoplayvideo = $("#wonderplugin-slider-autoplayvideo").is(":checked");
            wonderplugin_slider_config.isresponsive = $("#wonderplugin-slider-isresponsive").is(":checked");
            wonderplugin_slider_config.scalemode = $("#wonderplugin-slider-scalemode").val();
            wonderplugin_slider_config.showtext = $("#wonderplugin-slider-showtext").is(":checked");
            wonderplugin_slider_config.arrowstyle = $("#wonderplugin-slider-arrowstyle").val();
            wonderplugin_slider_config.showtimer = $("#wonderplugin-slider-showtimer").is(":checked");
            wonderplugin_slider_config.loop = parseInt($.trim($("#wonderplugin-slider-loop").val()));
            if (isNaN(wonderplugin_slider_config.loop) || wonderplugin_slider_config.loop < 0)
                wonderplugin_slider_config.loop =
                        0;
            wonderplugin_slider_config.slideinterval = parseInt($.trim($("#wonderplugin-slider-slideinterval").val()));
            if (isNaN(wonderplugin_slider_config.slideinterval) || wonderplugin_slider_config.slideinterval < 0)
                wonderplugin_slider_config.slideinterval = 8E3;
            wonderplugin_slider_config.fullwidth = $("#wonderplugin-slider-fullwidth").is(":checked");
            wonderplugin_slider_config.isfullscreen = $("#wonderplugin-slider-isfullscreen").is(":checked");
            wonderplugin_slider_config.textformat = $("#wonderplugin-slider-textformat").val();
            wonderplugin_slider_config.textcss = $.trim($("#wonderplugin-slider-textcss").val());
            wonderplugin_slider_config.textbgcss = $.trim($("#wonderplugin-slider-textbgcss").val());
            wonderplugin_slider_config.titlecss = $.trim($("#wonderplugin-slider-titlecss").val());
            wonderplugin_slider_config.descriptioncss = $.trim($("#wonderplugin-slider-descriptioncss").val());
            wonderplugin_slider_config.textpositionstatic = $("#wonderplugin-slider-textpositionstatic").val();
            var positions = [];
            for (var i = 0; i < DYNAMIC_POSITIONS.length; i++)
                if ($("#wonderplugin-slider-textpositiondynamic-" +
                        DYNAMIC_POSITIONS[i]).is(":checked"))
                    positions.push(DYNAMIC_POSITIONS[i]);
            if (positions.length == 0)
                positions.push("bottomleft");
            wonderplugin_slider_config.textpositiondynamic = positions.join(",");
            wonderplugin_slider_config.textautohide = $("#wonderplugin-slider-textautohide").is(":checked");
            wonderplugin_slider_config.showbottomshadow = $("#wonderplugin-slider-showbottomshadow").is(":checked");
            wonderplugin_slider_config.navshowpreview = $("#wonderplugin-slider-navshowpreview").is(":checked");
            wonderplugin_slider_config.border =
                    parseInt($.trim($("#wonderplugin-slider-border").val()));
            if (isNaN(wonderplugin_slider_config.border) || wonderplugin_slider_config.border < 0)
                wonderplugin_slider_config.border = 0;
            wonderplugin_slider_config.paddingleft = parseInt($.trim($("#wonderplugin-slider-paddingleft").val()));
            if (isNaN(wonderplugin_slider_config.paddingleft))
                wonderplugin_slider_config.paddingleft = 0;
            wonderplugin_slider_config.paddingright = parseInt($.trim($("#wonderplugin-slider-paddingright").val()));
            if (isNaN(wonderplugin_slider_config.paddingright))
                wonderplugin_slider_config.paddingright =
                        0;
            wonderplugin_slider_config.paddingtop = parseInt($.trim($("#wonderplugin-slider-paddingtop").val()));
            if (isNaN(wonderplugin_slider_config.paddingtop))
                wonderplugin_slider_config.paddingtop = 0;
            wonderplugin_slider_config.paddingbottom = parseInt($.trim($("#wonderplugin-slider-paddingbottom").val()));
            if (isNaN(wonderplugin_slider_config.paddingbottom))
                wonderplugin_slider_config.paddingbottom = 0;
            wonderplugin_slider_config.arrowimagemode = $("input[name=wonderplugin-slider-arrowimagemode]:checked").val();
            if (wonderplugin_slider_config.arrowimagemode ==
                    "custom")
                wonderplugin_slider_config.arrowimage = $.trim($("#wonderplugin-slider-customarrowimage").val());
            else
                wonderplugin_slider_config.arrowimage = $.trim($("#wonderplugin-slider-arrowimage").val());
            wonderplugin_slider_config.arrowwidth = parseInt($.trim($("#wonderplugin-slider-arrowwidth").val()));
            if (isNaN(wonderplugin_slider_config.arrowwidth) || wonderplugin_slider_config.arrowwidth < 0)
                wonderplugin_slider_config.arrowwidth = defaultSkinOptions[wonderplugin_slider_config.skin]["arrowwidth"];
            wonderplugin_slider_config.arrowheight =
                    parseInt($.trim($("#wonderplugin-slider-arrowheight").val()));
            if (isNaN(wonderplugin_slider_config.arrowheight) || wonderplugin_slider_config.arrowheight < 0)
                wonderplugin_slider_config.arrowheight = defaultSkinOptions[wonderplugin_slider_config.skin]["arrowheight"];
            wonderplugin_slider_config.arrowtop = parseInt($.trim($("#wonderplugin-slider-arrowtop").val()));
            if (isNaN(wonderplugin_slider_config.arrowtop))
                wonderplugin_slider_config.arrowtop = defaultSkinOptions[wonderplugin_slider_config.skin]["arrowtop"];
            wonderplugin_slider_config.arrowmargin =
                    parseInt($.trim($("#wonderplugin-slider-arrowmargin").val()));
            if (isNaN(wonderplugin_slider_config.arrowmargin))
                wonderplugin_slider_config.arrowmargin = defaultSkinOptions[wonderplugin_slider_config.skin]["arrowmargin"];
            wonderplugin_slider_config.navstyle = defaultSkinOptions[wonderplugin_slider_config.skin]["navstyle"];
            if (wonderplugin_slider_config.navstyle == "bullets") {
                wonderplugin_slider_config.navimagemode = $("input[name=wonderplugin-slider-navimagemode]:checked").val();
                if (wonderplugin_slider_config.navimagemode ==
                        "custom")
                    wonderplugin_slider_config.navimage = $.trim($("#wonderplugin-slider-customnavimage").val());
                else
                    wonderplugin_slider_config.navimage = $.trim($("#wonderplugin-slider-navimage").val());
                wonderplugin_slider_config.navwidth = parseInt($.trim($("#wonderplugin-slider-navwidth").val()));
                if (isNaN(wonderplugin_slider_config.navwidth) || wonderplugin_slider_config.navwidth < 0)
                    wonderplugin_slider_config.navwidth = defaultSkinOptions[wonderplugin_slider_config.skin]["navwidth"];
                wonderplugin_slider_config.navheight =
                        parseInt($.trim($("#wonderplugin-slider-navheight").val()));
                if (isNaN(wonderplugin_slider_config.navheight) || wonderplugin_slider_config.navheight < 0)
                    wonderplugin_slider_config.navheight = defaultSkinOptions[wonderplugin_slider_config.skin]["navheight"];
                wonderplugin_slider_config.navspacing = parseInt($.trim($("#wonderplugin-slider-navspacing").val()));
                if (isNaN(wonderplugin_slider_config.navspacing))
                    wonderplugin_slider_config.navspacing = defaultSkinOptions[wonderplugin_slider_config.skin]["navspacing"];
                wonderplugin_slider_config.navmarginx =
                        parseInt($.trim($("#wonderplugin-slider-navmarginx").val()));
                if (isNaN(wonderplugin_slider_config.navmarginx))
                    wonderplugin_slider_config.navmarginx = defaultSkinOptions[wonderplugin_slider_config.skin]["navmarginx"];
                wonderplugin_slider_config.navmarginy = parseInt($.trim($("#wonderplugin-slider-navmarginy").val()));
                if (isNaN(wonderplugin_slider_config.navmarginy))
                    wonderplugin_slider_config.navmarginy = defaultSkinOptions[wonderplugin_slider_config.skin]["navmarginy"]
            } else {
                wonderplugin_slider_config.navimage =
                        defaultSkinOptions[wonderplugin_slider_config.skin]["navimage"];
                wonderplugin_slider_config.navwidth = defaultSkinOptions[wonderplugin_slider_config.skin]["navwidth"];
                wonderplugin_slider_config.navheight = defaultSkinOptions[wonderplugin_slider_config.skin]["navheight"];
                wonderplugin_slider_config.navspacing = defaultSkinOptions[wonderplugin_slider_config.skin]["navspacing"];
                wonderplugin_slider_config.navmarginx = defaultSkinOptions[wonderplugin_slider_config.skin]["navmarginx"];
                wonderplugin_slider_config.navmarginy =
                        defaultSkinOptions[wonderplugin_slider_config.skin]["navmarginy"]
            }
            wonderplugin_slider_config.playvideoimage = $.trim($("#wonderplugin-slider-playvideoimage").val());
            wonderplugin_slider_config.playvideoimagewidth = parseInt($.trim($("#wonderplugin-slider-playvideoimagewidth").val()));
            if (isNaN(wonderplugin_slider_config.playvideoimagewidth) || wonderplugin_slider_config.playvideoimagewidth < 0)
                wonderplugin_slider_config.playvideoimagewidth = defaultSkinOptions[wonderplugin_slider_config.skin]["playvideoimagewidth"];
            wonderplugin_slider_config.playvideoimageheight = parseInt($.trim($("#wonderplugin-slider-playvideoimageheight").val()));
            if (isNaN(wonderplugin_slider_config.playvideoimageheight) || wonderplugin_slider_config.playvideoimageheight < 0)
                wonderplugin_slider_config.playvideoimageheight = defaultSkinOptions[wonderplugin_slider_config.skin]["playvideoimageheight"];
            var transition = [];
            for (var i = 0; i < TRANSITION_EFFECTS.length; i++)
                if ($("#wonderplugin-slider-effect-" + TRANSITION_EFFECTS[i]).is(":checked"))
                    transition.push(TRANSITION_EFFECTS[i]);
            if (transition.length == 0)
                transition.push("slice");
            wonderplugin_slider_config.transition = transition.join(",");
            wonderplugin_slider_config.lightboxresponsive = $("#wonderplugin-slider-lightboxresponsive").is(":checked");
            wonderplugin_slider_config.lightboxshownavigation = $("#wonderplugin-slider-lightboxshownavigation").is(":checked");
            wonderplugin_slider_config.lightboxshowtitle = $("#wonderplugin-slider-lightboxshowtitle").is(":checked");
            wonderplugin_slider_config.lightboxshowdescription = $("#wonderplugin-slider-lightboxshowdescription").is(":checked");
            wonderplugin_slider_config.lightboxthumbwidth = parseInt($.trim($("#wonderplugin-slider-lightboxthumbwidth").val()));
            wonderplugin_slider_config.lightboxthumbheight = parseInt($.trim($("#wonderplugin-slider-lightboxthumbheight").val()));
            wonderplugin_slider_config.lightboxthumbtopmargin = parseInt($.trim($("#wonderplugin-slider-lightboxthumbtopmargin").val()));
            wonderplugin_slider_config.lightboxthumbbottommargin = parseInt($.trim($("#wonderplugin-slider-lightboxthumbbottommargin").val()));
            wonderplugin_slider_config.lightboxbarheight =
                    parseInt($.trim($("#wonderplugin-slider-lightboxbarheight").val()));
            wonderplugin_slider_config.lightboxtitlebottomcss = $.trim($("#wonderplugin-slider-lightboxtitlebottomcss").val());
            wonderplugin_slider_config.lightboxdescriptionbottomcss = $.trim($("#wonderplugin-slider-lightboxdescriptionbottomcss").val());
            wonderplugin_slider_config.customcss = $.trim($("#wonderplugin-slider-custom-css").val());
            wonderplugin_slider_config.dataoptions = $.trim($("#wonderplugin-slider-data-options").val())
        };
        $("#wonderplugin-slider-textformat").change(function() {
            var textformat =
                    $(this).val();
            if (textformat in WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS) {
                $("#wonderplugin-slider-textcss").val(WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["textcss"]);
                $("#wonderplugin-slider-textbgcss").val(WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["textbgcss"]);
                $("#wonderplugin-slider-titlecss").val(WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["titlecss"]);
                $("#wonderplugin-slider-descriptioncss").val(WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["descriptioncss"]);
                $("#wonderplugin-slider-textpositionstatic").val(WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["textpositionstatic"]);
                var positions = WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["textpositiondynamic"].split(",");
                for (var i = 0; i < DYNAMIC_POSITIONS.length; i++)
                    $("#wonderplugin-slider-textpositiondynamic-" + DYNAMIC_POSITIONS[i]).attr("checked", positions.indexOf(DYNAMIC_POSITIONS[i]) > -1);
                if (WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["textstyle"] == "static") {
                    $(".wonderplugin-slider-texteffect-static").css({display: "block"});
                    $(".wonderplugin-slider-texteffect-dynamic").css({display: "none"})
                } else if (WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[textformat]["textstyle"] ==
                        "dynamic") {
                    $(".wonderplugin-slider-texteffect-static").css({display: "none"});
                    $(".wonderplugin-slider-texteffect-dynamic").css({display: "block"})
                }
            }
        });
        var escapeHTMLString = function(str) {
            return str.replace(/'/g, "&#39;").replace(/"/g, "&quot;")
        };
        var previewSlider = function() {
            updateSliderOptions();
            $("#wonderplugin-slider-preview-container").empty();
            if (wonderplugin_slider_config.fullwidth) {
                $("#wonderplugin-slider-preview-container").css({"max-width": "100%"});
                if (wonderplugin_slider_config.isfullscreen)
                    $("#wonderplugin-slider-preview-container").css({"height": "500px"})
            } else if (wonderplugin_slider_config.isresponsive)
                $("#wonderplugin-slider-preview-container").css({"max-width": wonderplugin_slider_config.width +
                            "px"});
            $("#wonderplugin-slider-preview-container").css({"padding-left": wonderplugin_slider_config.paddingleft + "px", "padding-right": wonderplugin_slider_config.paddingright + "px", "padding-top": wonderplugin_slider_config.paddingtop + "px", "padding-bottom": wonderplugin_slider_config.paddingbottom + "px"});
            var previewCode = "<div id='wonderplugin-slider-preview'";
            if (wonderplugin_slider_config.dataoptions && wonderplugin_slider_config.dataoptions.length > 0)
                previewCode += " " + wonderplugin_slider_config.dataoptions;
            previewCode += "></div>";
            $("#wonderplugin-slider-preview-container").html(previewCode);
            if (wonderplugin_slider_config.slides.length > 0) {
                var sliderid = wonderplugin_slider_config.id > 0 ? wonderplugin_slider_config.id : 0;
                $("head").find("style").each(function() {
                    if ($(this).data("creator") == "wonderpluginslidercreator" + sliderid)
                        $(this).remove()
                });
                $("head").find("link").each(function() {
                    if ($(this).data("creator") == "wonderpluginslidercreator" + sliderid)
                        $(this).remove()
                });
                if (wonderplugin_slider_config.customcss && wonderplugin_slider_config.customcss.length >
                        0) {
                    var customcss = wonderplugin_slider_config.customcss.replace("SLIDERID", sliderid);
                    $("head").append("<style type='text/css' data-creator='wonderpluginslidercreator" + sliderid + "'>" + customcss + "</style>")
                }
                var i;
                var code = '<ul class="amazingslider-slides" style="display:none;">';
                for (i = 0; i < wonderplugin_slider_config.slides.length; i++) {
                    code += "<li>";
                    if (wonderplugin_slider_config.slides[i].lightbox) {
                        code += '<a href="';
                        if (wonderplugin_slider_config.slides[i].type == 0)
                            code += wonderplugin_slider_config.slides[i].image;
                        else if (wonderplugin_slider_config.slides[i].type == 1) {
                            code += wonderplugin_slider_config.slides[i].mp4;
                            if (wonderplugin_slider_config.slides[i].webm)
                                code += '" data-webm="' + wonderplugin_slider_config.slides[i].webm
                        } else if (wonderplugin_slider_config.slides[i].type == 2 || wonderplugin_slider_config.slides[i].type == 3)
                            code += wonderplugin_slider_config.slides[i].video;
                        if (wonderplugin_slider_config.slides[i].lightboxsize)
                            code += '" data-width="' + wonderplugin_slider_config.slides[i].lightboxwidth + '" data-height="' +
                                    wonderplugin_slider_config.slides[i].lightboxheight;
                        if (wonderplugin_slider_config.slides[i].description && wonderplugin_slider_config.slides[i].description.length > 0)
                            code += '" data-description="' + escapeHTMLString(wonderplugin_slider_config.slides[i].description);
                        code += '" class="html5lightbox">'
                    } else if (wonderplugin_slider_config.slides[i].weblink && wonderplugin_slider_config.slides[i].weblink.length > 0) {
                        code += '<a href="' + wonderplugin_slider_config.slides[i].weblink + '"';
                        if (wonderplugin_slider_config.slides[i].linktarget &&
                                wonderplugin_slider_config.slides[i].linktarget.length > 0)
                            code += ' target="' + wonderplugin_slider_config.slides[i].linktarget + '"';
                        code += ">"
                    }
                    if (i > 0 && wonderplugin_slider_config.loadimageondemand)
                        code += '<img data-src="';
                    else
                        code += '<img src="';
                    code += wonderplugin_slider_config.slides[i].image + '"';
                    code += ' alt="' + escapeHTMLString(wonderplugin_slider_config.slides[i].title) + '"';
                    code += ' data-description="' + escapeHTMLString(wonderplugin_slider_config.slides[i].description) + '"';
                    code += " />";
                    if (wonderplugin_slider_config.slides[i].lightbox ||
                            !wonderplugin_slider_config.slides[i].lightbox && wonderplugin_slider_config.slides[i].weblink && wonderplugin_slider_config.slides[i].weblink.length > 0)
                        code += "</a>";
                    if (!wonderplugin_slider_config.slides[i].lightbox)
                        if (wonderplugin_slider_config.slides[i].type == 1) {
                            code += '<video preload="none" src="' + wonderplugin_slider_config.slides[i].mp4 + '"';
                            if (wonderplugin_slider_config.slides[i].webm)
                                code += ' data-webm="' + wonderplugin_slider_config.slides[i].webm + '"';
                            code += "></video>"
                        } else if (wonderplugin_slider_config.slides[i].type ==
                                2 || wonderplugin_slider_config.slides[i].type == 3)
                            code += '<video preload="none" src="' + wonderplugin_slider_config.slides[i].video + '"></video>';
                    if (wonderplugin_slider_config.slides[i].button && wonderplugin_slider_config.slides[i].button.length > 0) {
                        if (wonderplugin_slider_config.slides[i].buttonlink && wonderplugin_slider_config.slides[i].buttonlink.length > 0) {
                            code += '<a href="' + wonderplugin_slider_config.slides[i].buttonlink + '"';
                            if (wonderplugin_slider_config.slides[i].buttonlinktarget && wonderplugin_slider_config.slides[i].buttonlinktarget.length >
                                    0)
                                code += ' target="' + wonderplugin_slider_config.slides[i].buttonlinktarget + '"';
                            code += ">"
                        }
                        code += '<button class="' + wonderplugin_slider_config.slides[i].buttoncss + '">' + wonderplugin_slider_config.slides[i].button + "</button>";
                        if (wonderplugin_slider_config.slides[i].buttonlink && wonderplugin_slider_config.slides[i].buttonlink.length > 0)
                            code += "</a>"
                    }
                    code += "</li>"
                }
                code += "</ul>";
                code += '<ul class="amazingslider-thumbnails" style="display:none;">';
                for (i = 0; i < wonderplugin_slider_config.slides.length; i++) {
                    code += '<li><img src="' +
                            wonderplugin_slider_config.slides[i].thumbnail + '"';
                    if (wonderplugin_slider_config.slides[i].title.length > 0)
                        code += ' alt="' + escapeHTMLString(wonderplugin_slider_config.slides[i].title) + '"';
                    if (wonderplugin_slider_config.slides[i].description.length > 0)
                        code += ' title="' + escapeHTMLString(wonderplugin_slider_config.slides[i].description) + '"';
                    code += " /></li>"
                }
                code += "</ul>";
                $("#wonderplugin-slider-preview").html(code);
                var jsfolder = $("#wonderplugin-slider-jsfolder").text();
                var sliderOptions = $.extend({}, WONDERPLUGIN_SLIDER_SKIN_OPTIONS[wonderplugin_slider_config["skin"]],
                        WONDERPLUGIN_SLIDER_TEXT_EFFECT_FORMATS[wonderplugin_slider_config["textformat"]], {sliderid: sliderid, jsfolder: jsfolder}, wonderplugin_slider_config);
                $("#wonderplugin-slider-preview").wonderpluginslider(sliderOptions)
            }
        };
        var publishSlider = function() {
            $("#wonderplugin-slider-publish-loading").show();
            updateSliderOptions();
            jQuery.ajax({url: ajaxurl, type: "POST", data: {action: "wonderplugin_slider_save_config", item: JSON.stringify(wonderplugin_slider_config)}, success: function(data) {
                    $("#wonderplugin-slider-publish-loading").hide();
                    if (data.success && data.id >= 0) {
                        wonderplugin_slider_config.id = data.id;
                        $("#wonderplugin-slider-publish-information").html("<div class='updated'><p>The slider has been saved and published.</p></div>" + "<div class='updated'><p>To embed the slider into your page or post, use shortcode <b>[wonderplugin_slider id=\"" + data.id + '"]</b></p></div>' + "<div class='updated'><p>To embed the slider into your template, use php code <b>&lt;?php echo do_shortcode('[wonderplugin_slider id=\"" + data.id + "\"]'); ?&gt;</b></p></div>")
                    } else {
                        var errorHtml =
                                "<div class='error'><p>WordPress Ajax call failed. Please check your WordPress configuration file and make sure the WP_DEBUG is set to false.</p></div>";
                        errorHtml += "<div class='error'><p>Or you can click the button below and save the slider with POST method</p></div>";
                        errorHtml += "<form method='post'>";
                        errorHtml += "<input type='hidden' name='wonderplugin-slider-save-item-post-value' id='wonderplugin-slider-save-item-post-value' value='" + JSON.stringify(wonderplugin_slider_config) + "' />";
                        errorHtml += "<p class='submit'><input type='submit' name='wonderplugin-slider-save-item-post' id='wonderplugin-slider-save-item-post' class='button button-primary' value='Save & Publish with Post Method'  /></p>";
                        errorHtml += "</form>";
                        $("#wonderplugin-slider-publish-information").html(errorHtml)
                    }
                }})
        };
        var default_options = {id: -1, name: "My Slider", width: 840, height: 360, slides: [], transition: "slice", skin: "classic", showtext: true, textformat: "Bottom bar", paddingleft: 0, paddingright: 0, paddingtop: 0, paddingbottom: 0, lightboxresponsive: true, lightboxshownavigation: false, lightboxshowtitle: true, lightboxshowdescription: false, lightboxthumbwidth: 90, lightboxthumbheight: 60, lightboxthumbtopmargin: 12, lightboxthumbbottommargin: 4, lightboxbarheight: 64,
            lightboxtitlebottomcss: "{color:#333; font-size:14px; font-family:Armata,sans-serif,Arial; overflow:hidden; text-align:left;}", lightboxdescriptionbottomcss: "{color:#333; font-size:12px; font-family:Arial,Helvetica,sans-serif; overflow:hidden; text-align:left; margin:4px 0px 0px; padding: 0px;}", customcss: "", dataoptions: ""};
        var wonderplugin_slider_config = $.extend({}, default_options, defaultSkinOptions[default_options["skin"]]);
        var sliderId = parseInt($("#wonderplugin-slider-id").text());
        if (sliderId >= 0) {
            var config_options =
                    $.parseJSON($("#wonderplugin-slider-id-config").text());
            if ("isresponsive"in config_options && !("fullwidth"in config_options))
                config_options.fullwidth = config_options.isresponsive;
            $.extend(wonderplugin_slider_config, config_options);
            wonderplugin_slider_config.id = sliderId
        }
        var i;
        for (i = 0; i < wonderplugin_slider_config.slides.length; i++) {
            if (!("lightboxsize"in wonderplugin_slider_config.slides[i]))
                wonderplugin_slider_config.slides[i]["lightboxsize"] = false;
            if (!("lightboxwidth"in wonderplugin_slider_config.slides[i]))
                wonderplugin_slider_config.slides[i]["lightboxwidth"] =
                        960;
            if (!("lightboxheight"in wonderplugin_slider_config.slides[i]))
                wonderplugin_slider_config.slides[i]["lightboxheight"] = 540
        }
        for (i = 0; i < wonderplugin_slider_config.slides.length; i++) {
            wonderplugin_slider_config.slides[i].title = wonderplugin_slider_config.slides[i].title.replace(/\\'/g, "'").replace(/\\"/g, '"');
            wonderplugin_slider_config.slides[i].description = wonderplugin_slider_config.slides[i].description.replace(/\\'/g, "'").replace(/\\"/g, '"')
        }
        var cssOptions = ["textcss", "textbgcss", "titlecss", "descriptioncss"];
        for (i = 0; i < cssOptions.length; i++)
            wonderplugin_slider_config[cssOptions[i]] = wonderplugin_slider_config[cssOptions[i]].replace(/\\'/g, "'").replace(/\\"/g, '"');
        for (i = 0; i < wonderplugin_slider_config.slides.length; i++) {
            if (wonderplugin_slider_config.slides[i].lightbox !== true && wonderplugin_slider_config.slides[i].lightbox !== false)
                wonderplugin_slider_config.slides[i].lightbox = wonderplugin_slider_config.slides[i].lightbox && wonderplugin_slider_config.slides[i].lightbox.toLowerCase() === "true";
            if (wonderplugin_slider_config.slides[i].lightboxsize !==
                    true && wonderplugin_slider_config.slides[i].lightboxsize !== false)
                wonderplugin_slider_config.slides[i].lightboxsize = wonderplugin_slider_config.slides[i].lightboxsize && wonderplugin_slider_config.slides[i].lightboxsize.toLowerCase() === "true"
        }
        var boolOptions = ["autoplay", "randomplay", "loadimageondemand", "autoplayvideo", "isresponsive", "fullwidth", "isfullscreen", "showtext", "showtimer", "showbottomshadow", "navshowpreview", "textautohide", "lightboxresponsive", "lightboxshownavigation", "lightboxshowtitle", "lightboxshowdescription"];
        for (i = 0; i < boolOptions.length; i++)
            if (wonderplugin_slider_config[boolOptions[i]] !== true && wonderplugin_slider_config[boolOptions[i]] !== false)
                wonderplugin_slider_config[boolOptions[i]] = wonderplugin_slider_config[boolOptions[i]] && wonderplugin_slider_config[boolOptions[i]].toLowerCase() === "true";
        if (wonderplugin_slider_config.dataoptions && wonderplugin_slider_config.dataoptions.length > 0)
            wonderplugin_slider_config.dataoptions = wonderplugin_slider_config.dataoptions.replace(/\\"/g, '"').replace(/\\'/g, "'");
        var printConfig =
                function() {
                    $("#wonderplugin-slider-name").val(wonderplugin_slider_config.name);
                    $("#wonderplugin-slider-width").val(wonderplugin_slider_config.width);
                    $("#wonderplugin-slider-height").val(wonderplugin_slider_config.height);
                    updateMediaTable();
                    $(".wonderplugin-tab-skin").find("img").removeClass("selected");
                    $("input:radio[name=wonderplugin-slider-skin][value=" + wonderplugin_slider_config.skin + "]").attr("checked", true);
                    $("input:radio[name=wonderplugin-slider-skin][value=" + wonderplugin_slider_config.skin +
                            "]").parent().find("img").addClass("selected");
                    $("#wonderplugin-slider-autoplay").attr("checked", wonderplugin_slider_config.autoplay);
                    $("#wonderplugin-slider-randomplay").attr("checked", wonderplugin_slider_config.randomplay);
                    $("#wonderplugin-slider-loadimageondemand").attr("checked", wonderplugin_slider_config.loadimageondemand);
                    $("#wonderplugin-slider-autoplayvideo").attr("checked", wonderplugin_slider_config.autoplayvideo);
                    $("#wonderplugin-slider-isresponsive").attr("checked", wonderplugin_slider_config.isresponsive);
                    $("#wonderplugin-slider-scalemode").val(wonderplugin_slider_config.scalemode);
                    $("#wonderplugin-slider-showtext").attr("checked", wonderplugin_slider_config.showtext);
                    $("#wonderplugin-slider-showtimer").attr("checked", wonderplugin_slider_config.showtimer);
                    $("#wonderplugin-slider-loop").val(wonderplugin_slider_config.loop);
                    $("#wonderplugin-slider-slideinterval").val(wonderplugin_slider_config.slideinterval);
                    $("#wonderplugin-slider-fullwidth").attr("checked", wonderplugin_slider_config.fullwidth);
                    $("#wonderplugin-slider-isfullscreen").attr("checked",
                            wonderplugin_slider_config.isfullscreen);
                    var transition = wonderplugin_slider_config.transition.split(",");
                    for (var i = 0; i < TRANSITION_EFFECTS.length; i++)
                        $("#wonderplugin-slider-effect-" + TRANSITION_EFFECTS[i]).attr("checked", transition.indexOf(TRANSITION_EFFECTS[i]) > -1);
                    $("#wonderplugin-slider-lightboxresponsive").attr("checked", wonderplugin_slider_config.lightboxresponsive);
                    $("#wonderplugin-slider-lightboxshownavigation").attr("checked", wonderplugin_slider_config.lightboxshownavigation);
                    $("#wonderplugin-slider-lightboxshowtitle").attr("checked",
                            wonderplugin_slider_config.lightboxshowtitle);
                    $("#wonderplugin-slider-lightboxshowdescription").attr("checked", wonderplugin_slider_config.lightboxshowdescription);
                    $("#wonderplugin-slider-lightboxthumbwidth").val(wonderplugin_slider_config.lightboxthumbwidth);
                    $("#wonderplugin-slider-lightboxthumbheight").val(wonderplugin_slider_config.lightboxthumbheight);
                    $("#wonderplugin-slider-lightboxthumbtopmargin").val(wonderplugin_slider_config.lightboxthumbtopmargin);
                    $("#wonderplugin-slider-lightboxthumbbottommargin").val(wonderplugin_slider_config.lightboxthumbbottommargin);
                    $("#wonderplugin-slider-lightboxbarheight").val(wonderplugin_slider_config.lightboxbarheight);
                    $("#wonderplugin-slider-lightboxtitlebottomcss").val(wonderplugin_slider_config.lightboxtitlebottomcss);
                    $("#wonderplugin-slider-lightboxdescriptionbottomcss").val(wonderplugin_slider_config.lightboxdescriptionbottomcss);
                    printSkinOptions(wonderplugin_slider_config);
                    $("#wonderplugin-slider-custom-css").val(wonderplugin_slider_config.customcss);
                    $("#wonderplugin-slider-data-options").val(wonderplugin_slider_config.dataoptions)
                };
        printConfig()
    });
    $.fn.wpdraggable = function(callback) {
        this.css("cursor", "move").on("mousedown", function(e) {
            var $dragged = $(this);
            var x = $dragged.offset().left - e.pageX;
            var y = $dragged.offset().top - e.pageY;
            var z = $dragged.css("z-index");
            $(document).on("mousemove.wpdraggable", function(e) {
                $dragged.css({"z-index": 999}).offset({left: x + e.pageX, top: y + e.pageY});
                e.preventDefault()
            }).one("mouseup", function() {
                $(this).off("mousemove.wpdraggable click.wpdraggable");
                $dragged.css("z-index", z);
                var i = $dragged.data("order");
                var coltotal = Math.floor($dragged.parent().parent().parent().innerWidth() / $dragged.parent().parent().outerWidth());
                var row = Math.floor(($dragged.offset().top - $dragged.parent().parent().parent().offset().top) / $dragged.parent().parent().outerHeight());
                var col = Math.floor(($dragged.offset().left - $dragged.parent().parent().parent().offset().left) / $dragged.parent().parent().outerWidth());
                var j = row * coltotal + col;
                callback(i, j)
            });
            e.preventDefault()
        });
        return this
    }
})(jQuery);
