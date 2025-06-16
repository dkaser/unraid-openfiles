function toggleRow(pid) {
  // Toggle the visibility of the readmore span and switch using jquery
  const readMoreSpan = $(`#readmore-${pid}`);
  if (readMoreSpan.is(":visible")) {
    readMoreSpan.hide();
    $(`#toggle-${pid}`).text(translator.tr("more"));
    scrollToPos($(`#top-${pid}`).offset().top);
  } else {
    readMoreSpan.show();
    $(`#toggle-${pid}`).text(translator.tr("less"));
    scrollToPos($(`#top-${pid}`).offset().top);
  }
}

function scrollToPos(location) {
  // Scroll to the specified location
  $([document.documentElement, document.body]).animate(
    {
      scrollTop: location,
    },
    500
  );
}

function getDatatableConfig(url) {
  return {
    ajax: {
      url: url,
      dataSrc: "",
    },
    fixedHeader: {
      headerOffset: 50,
    },
    columns: [
      { name: "PID", data: "PID" },
      { name: "name", data: "name" },
      { name: "container", data: "containerName" },
      {
        name: "kill",
        data: "PID",
        render: function (data, type, row) {
          if (type === "display") {
            let button = `<input title='${translator.tr(
              "enable_kill"
            )}' type='checkbox' onclick='$("#kill_button${data}").prop("disabled",!this.checked);'>`;
            button += `<button class='kill-button' title='${translator.tr(
              "kill_process_desc"
            )}' id='kill_button${data}' disabled onclick='openBox("/plugins/open.files/scripts/killprocess&arg1=${data}","Kill Process",450,450,true)'>${translator.tr(
              "kill"
            )}</button>`;
            return button;
          }
          return data;
        },
      },
      { name: "open", data: "count" },
      {
        name: "blocking",
        data: "blocking",
        render: function (data, type, row) {
          return data === true ? translator.tr("yes") : translator.tr("no");
        },
      },
      {
        name: "files",
        data: function (row, type, val, meta) {
          const esc = (s) => $("<div>").text(s).html();

          if (row.files.length < 5) {
            return row.files.map(esc).join("<br>");
          } else {
            // If there are more than 4 files, show a "read more" link
            return `<span id="top-${row.PID}">${row.files
              .map(esc)
              .slice(0, 4)
              .join("<br>")}</span>
                                <span id="readmore-${
                                  row.PID
                                }" style="display:none;"><br>${row.files
              .map(esc)
              .slice(4)
              .join("<br>")}</span>
                                <br><a href="#" id="toggle-${
                                  row.PID
                                }" onclick="toggleRow(${
              row.PID
            })">${translator.tr("more")}</a>`;
          }
        },
      },
    ],
    columnControl: {
      target: 0,
      content: [
        {
          extend: "dropdown",
          content: ["searchClear", "search"],
          icon: "search",
        },
      ],
    },
    columnDefs: [
      {
        targets: "_all",
        className: "dt-left",
      },
      {
        targets: 3,
        columnControl: {
          target: 0,
          content: [],
        },
      },
      {
        targets: [2, 5],
        columnControl: {
          target: 0,
          content: [
            {
              extend: "dropdown",
              content: ["searchClear", "searchList"],
              icon: "search",
            },
          ],
        },
      },
      {
        targets: 6,
        className: "overflow-anywhere",
      },
    ],
    paging: true,
    pageLength: 50,
    ordering: true,
    layout: {
      topStart: {
        buttons: [
          {
            text: translator.tr("refresh"),
            action: function (e, dt, node, config) {
              dt.ajax.reload();
            },
          },
          {
            text: translator.tr("clear_filters"),
            action: function (e, dt, node, config) {
              dt.search("");
              dt.columns().ccSearchClear();
              dt.draw();
            },
          },
        ],
        pageLength: {
          menu: [25, 50, 100, 200, -1],
        },
      },
      topEnd: null,
    },
  };
}
