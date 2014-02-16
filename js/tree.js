function initTree() {
    $('#tt').tree({
        animate: true,
        url: 'cmd.php?cmd=getTree',
        dnd: true,
        onBeforeDrop: function (target, source, point) {
            if (($(target).attr('data-book') == 'undefined' && point == 'append') ||
                ($(target).attr('data-book') != 'undefined' && point == 'top') ||
                ($(target).attr('data-book') != 'undefined' && point == 'bottom')) {
                return false
            }

            if ($(target).attr('data-book') != 'undefined' && source.book != undefined) {
                return false;
            }
        },
        onDrop: function (target, source, point) {
            var node = $('#tt').tree('find', source.id);
            var parentNode = $('#tt').tree('getParent', node.target);

            console.log(parentNode);

            if (parentNode != null) {
                var list = $('div#' + parentNode.domId).parent().find('div[data-chapter]')
                list.each(function (index, val) {
                    console.log($(val).attr('data-chapter'));
                })
            }
        },
        onContextMenu: function (e, node) {
            e.preventDefault();
            $(this).tree('select', node.target);
            $('#mm').menu('show', {
                left: e.pageX,
                top: e.pageY
            });
        },
        onSelect: function (node) {

            var parentNode = $('#tt').tree('getParent', node.target);
            var chapterId = node.chapter;
            var bookId = parentNode.book;

            $.ajax({
                url: "cmd.php",
                type: "POST",
                data: { bookId: bookId,
                    chapterId : chapterId,
                    cmd: 'getChapter'
                },
                dataType: "json"
            }).done(function (data) {
                    $("textarea").jqteVal(data.content);
                    console.log(data)
                });
        }

    });
}

var EditorAction =
{
    append: function () {
        var node = $('#tt').tree('getSelected');
        if ($(node.target).parent().parent().parent().hasClass('bookmenu') == true) {

            var content = '<div id="dd" class="easyui-dialog" title="My Dialog" data-options="iconCls:\'icon-save\',resizable:true,modal:true"><input id="chapterName" value="" type="text"/></div>';

            $('body').append(content);
            var dialog = $('#dd').dialog({
                title: 'My Dialog',
                width: 400,
                height: 200,
                cache: false,
                modal: true,
                buttons: [
                    {
                        text: 'Ok',
                        iconCls: 'icon-ok',
                        handler: function () {
                            if ($('#chapterName').val() != "") {

                                var selected = $('#tt').tree('getSelected');
                                $('#tt').tree('append', {
                                    parent: selected.target,
                                    data: [
                                        {
                                            id: 23,
                                            text: $('#chapterName').val()
                                        }
                                    ]
                                });
                                dialog.dialog('close');
                                $('#dd').remove();
                            }
                        }
                    },
                    {
                        text: 'Cancel',
                        handler: function () {
                            dialog.dialog('close');
                            $('#dd').remove();
                        }
                    }
                ]
            });


        } else {
            console.log('kann kein subcapitel erstellen')
        }
    },

    rename: function () {
        var node = $('#tt').tree('getSelected');
        var content = '<div id="dd" class="easyui-dialog" title="My Dialog" data-options="iconCls:\'icon-save\',resizable:true,modal:true"><input id="chapterName" value="" type="text"/></div>';

        $('body').append(content);

        var dialog = $('#dd').dialog({
            title: 'My Dialog',
            width: 400,
            height: 200,
            cache: false,
            modal: true,
            buttons: [
                {
                    text: 'Ok',
                    iconCls: 'icon-ok',
                    handler: function () {
                        if ($('#chapterName').val() != "") {
                            dialog.dialog('close');
                            var me = $('#tt').tree('update', {
                                target: node.target,
                                text: $('#chapterName').val()
                            });

                            console.log(node);

                            $('#dd').remove();
                        }
                    }
                },
                {
                    text: 'Cancel',
                    handler: function () {
                        dialog.dialog('close');
                        $('#dd').remove();
                    }
                }
            ]
        });
    },

    removeit: function () {
        var node = $('#tt').tree('getSelected');
        var content = '<div id="dd" class="easyui-dialog" title="My Dialog" data-options="iconCls:\'icon-save\',resizable:true,modal:true">Wollen sie wirklich löschen?</div>';

        $('body').append(content);
        var dialog = $('#dd').dialog({
            title: 'My Dialog',
            width: 400,
            height: 200,
            cache: false,
            modal: true,
            buttons: [
                {
                    text: 'Ok',
                    iconCls: 'icon-ok',
                    handler: function () {
                        if ($('#chapterName').val() != "") {
                            $('#tt').tree('remove', node.target);
                            dialog.dialog('close');
                            console.log(node);
                            $('#dd').remove();
                        }
                    }
                },
                {
                    text: 'Cancel',
                    handler: function () {
                        dialog.dialog('close');
                        $('#dd').remove();
                    }
                }
            ]
        });
    },

    collapse: function () {
        var node = $('#tt').tree('getSelected');
        $('#tt').tree('collapse', node.target);
    },
    expand: function () {
        var node = $('#tt').tree('getSelected');
        $('#tt').tree('expand', node.target);
    }

};