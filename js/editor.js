function initEditor() {
    var me = $("textarea").jqte(
        {center: false,
            left: false,
            right: false,
            indent: false,
            outdent: false,
            rule: false,
            unlink: false,
            link: false,
            sub: false,
            sup: false,
            color: false,
            fsize: false,
            remove: false,
            formats: [
                ["p", "Paragraph"],
                ["h1", "titel"],
                ["pre", "pre"]
            ]
        }
    );


    $("div[data-tool='21']").click(function () {
        var content = $('.jqte_editor').html();
        var node = $('#tt').tree('getSelected');
        var parentNode = $('#tt').tree('getParent', node.target);

        var chapterId = node.chapter;
        var bookId = parentNode.book;

        $.ajax({
            url: "cmd.php",
            type: "POST",
            data: { bookId: bookId,
                chapterId : chapterId,
                content : content,
                cmd: 'saveChapter'
            },
            dataType: "json"
        }).done(function (data) {
                console.log('save');
            });

    });


}