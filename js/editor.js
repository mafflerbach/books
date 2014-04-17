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
        ["pre", "pre"]
      ]
    }
  );

  $("div[data-tool='21']").click(function () {
    var content = $('.jqte_editor').html();

    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        id: $('#editor').data('section'),
        content: content,
        cmd: 'saveSection'
      },
      dataType: "json"
    }).done(function (data) {
        console.log('save');
      });

  });


}