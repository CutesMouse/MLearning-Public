var attachment = [];
function toggleWindow(id) {
    let c = document.getElementById(id);
    let cl = c.classList;
    if (cl.contains("open")) {
        cl.remove("open");
        $('#'+id).html("");
    } else {
        cl.add("open");
    }
}
function save() {
    toggleWindow("attachments");
    $('#attach_input').val(getAttachJson());
}
function createAttachment() {
    toggleWindow("attachments");
    toggleWindow("add_attachment");
}
$(function() {
    if (source != null) {
        backToVar(source);
    }
    $("body").append("<div class='dialog window' id=\"attachments\"></div>");
    /*$("#attachments").dialog({
        autoOpen: false,
        buttons: {
            "新增": function() {
                $('#add_attachment').dialog("open");
                },
            "儲存": function() {
                $(this).dialog("close");
                $('#attach_input').val(getAttachJson());
            }
        }
    });
    $("#add_attachment").dialog({
        autoOpen: false,
        buttons: {
            "新增": function() {
                newAttachment();
                $(this).dialog("close");
                },
            "關閉": function() { $(this).dialog("close"); }
        }
    });*/
    $("#attButton").click(function () {
        refreshAttachments();
        toggleWindow("attachments");
        //$("#attachments").dialog("open");
    });
});
function getAttachJson() {
    let json = {};
    for (let i = 0; i < attachment.length;i++) {
        let attach = attachment[i];
        json[attach.id] = {
            type: attach.type,
            name: attach.title,
            questions: attach.questions
        }
    }
    return JSON.stringify(json);
}
function backToVar(json) {
    json = JSON.parse(json);
    let js_keys = Object.keys(json);
    for (let i =0; i < js_keys.length; i++) {
        let attach = json[js_keys[i]];
        attachment.push({
            title: attach.name,
            id: js_keys[i],
            questions: attach.questions,
            type: attach.type,
        })
    }
}
function refreshAttachments() {
    $("#attachments").html("<button class='leave' onclick=\"toggleWindow('attachments')\">x</button><div class='bottom-box'><button class='options' onclick='createAttachment()'>新增</button><button class='options' onclick='save()'>儲存並關閉</button></div><span class='title'>編輯附件</span><hr class='title-sep'><div class='content'><ul>");
    for (let i = 0; i < attachment.length;i++) {
        $("#attachments").append("<li><a href='#' id='"+attachment[i].id+"'>"+attachment[i].title+"</a></li>");
        $('#'+attachment[i].id).click(function() {
            loadEditingWindow(attachment[i]);
        });
    }
    $("#attachments").append("</ul></div>");

}
function newAttachment() {
    $.ajax("post/attachment/attachments.php?type=rid",{
        dataType: "json",
        success: function(ret) {
            let id = ret["id"];
            attachment.push({
                title: $('#add_attachment_title').val(),
                id: id,
                type: $('#add_attachment_type').val(),
                questions: []
            });
            refreshAttachments();
        }
    })
}
function loadEditingWindow(attach) {
    let id = "#att_editing_"+attach.id;
    let innerEditing = "#inner_editing_"+attach.id;
    if (!$(id).length) {
        $('body').append("<div id='att_editing_"+attach.id+"'></div>");
    }
    $(id).html("題目總表:<br>");
    $(id).append("<select size='2' id='inner_editing_"+attach.id+"'></select>");
    let questions = attach.questions;
    if (questions) {
        for (let i = 0; i < questions.length; i++) {
            $(innerEditing).append("<option value='"+i+"'>"+questions[i].question+"</option>");
        }
    }
    $(innerEditing).css("min-width","600px");
    $(innerEditing).css("min-height","400px");
    $(id).dialog({
        width: 700,
        height: 600,
        buttons: {
            "關閉": function() {
                $(this).dialog("close");
            },
            "新增": function() {
                addQuestionPanel(attach);
            },
            "刪除": function() {
                let selected = $(innerEditing).val();
                if (!selected) {
                    alert("請先選擇一個項目!");
                    return;
                }
                let is = confirm("此舉將會刪除包含選項的所有項目，\n您確定嗎?");
                if (is) {
                    attach.questions.splice(selected,1);
                    loadEditingWindow(attach);
                }
            },
            "刪除整個附件": function() {
                let is = confirm("此舉將會刪除所有項目、包括題目、答案、等所有項目，\n您確定嗎?");
                if (is) {
                    attachment = attachment.filter(function(item) {
                        return item.id !== attach.id;
                    });
                    $(this).dialog("close");
                    refreshAttachments();
                }
            },
            "編輯": function() {
                let selected = $(innerEditing).val();
                if (!selected) alert("請先選擇一個項目!");
                else {
                    editQuestionPanel(attach.questions[selected],attach);
                }
            }
        }
    });
}
function addQuestionPanel(attach) {
    let id = "#att_addPerQuestion";
    if (!$(id).length) {
        $("body").append("<div id='att_addPerQuestion'></div>");
    }
    $(id).html("<label for='att_add_quest'>題目</label><input type='text' id='att_add_quest'><br><label for='att_add_opts'>選項 (一行一個 正確答案請於該行前加/)</label><br><textarea id='att_add_opts'></textarea>");
    $(id).dialog({
        buttons: {
            "確定": function() {
                let opts = [];
                let answer = null;
                let optsource = $('#att_add_opts').val();
                let quest = $('#att_add_quest').val();
                optsource = optsource.split("\n");
                for (let i = 0; i < optsource.length; i++) {
                    let item = optsource[i].toString();
                    if (!item) continue;
                    if (item.startsWith("/")) {
                        item = item.substr(1);
                        answer = item;
                    }
                    opts.push(item);
                }
                if (answer == null ) {
                    alert("資料未完整! 沒有選定正確答案\n請在正確答案選項前加上一個 \"/\"");
                    return false;
                }
                if (opts.length === 0 || !quest) {
                    alert("沒有輸入任何選項或是沒有輸入題目!");
                    return false;
                }
                addQuestion(attach,quest,answer,opts);
                let id = "#att_editing_"+attach.id;
                $(id).dialog("close");
                $(this).dialog("close");
                loadEditingWindow(attach);
            },
            "取消": function() {
                $(this).dialog("close");
            }
        }
    })
}
function editQuestionPanel(edit_quest,attach) {
    let id = "#att_addPerQuestion";
    if (!$(id).length) {
        $("body").append("<div id='att_addPerQuestion'></div>");
    }
    let question = edit_quest["question"];
    let answer = edit_quest["answer"];
    let options = "";
    for (let i = 0; i < edit_quest["options"].length; i++) {
        let b = edit_quest["options"][i];
        if (b === answer) options += "/"+b+"\n";
        else options += b+"\n";
    }
    $(id).html("<label for='att_add_quest'>題目</label><input type='text' id='att_add_quest' value='"+question+
        "'><br><label for='att_add_opts'>選項 (一行一個 正確答案請於該行前加/)</label><br><textarea id='att_add_opts'>"+options+"</textarea>");
    $(id).dialog({
        buttons: {
            "確定": function() {
                let opts = [];
                let answer = null;
                let optsource = $('#att_add_opts').val();
                let quest = $('#att_add_quest').val();
                optsource = optsource.split("\n");
                for (let i = 0; i < optsource.length; i++) {
                    let item = optsource[i].toString();
                    if (!item) continue;
                    if (item.startsWith("/")) {
                        item = item.substr(1);
                        answer = item;
                    }
                    opts.push(item);
                }
                if (answer == null || opts.length === 0 || !quest) {
                    alert("資料未完整!")
                    return false;
                }
                edit_quest["question"] = quest;
                edit_quest["answer"] = answer;
                edit_quest["options"] = opts;
                let id = "#att_editing_"+attach.id;
                $(id).dialog("close");
                $(this).dialog("close");
                loadEditingWindow(attach);
            },
            "取消": function() {
                $(this).dialog("close");
            }
        }
    })
}
function addQuestion(attach, question, answer, opts) {
    attach.questions.push({
        question: question,
        answer: answer,
        options: opts,
    })
}