import { tpl, fnAlertMessage } from "./lib.js"

// $(document.body).append(`
// <audio id="notification" style="position:absolute;display:hidden" src="/static/app/sounds/notify_add_sound.mp3" muted></audio>
// `);

// Audio.prototype.play = (function(play) {
//     return function () {
//         var audio = this,
//             args = arguments,
//             promise = play.apply(audio, args);
//         if (promise !== undefined) {
//             promise.catch(_ => {
//             // Autoplay was prevented. This is optional, but add a button to start playing.
//             var el = document.createElement("button");
//             el.innerHTML = "Play";
//             el.addEventListener("click", function(){play.apply(audio, args);});
//             this.parentNode.insertBefore(el, this.nextSibling)
//         });
//     }
// };
// })(Audio.prototype.play);

export class NotificationManager {
    static oEventSource = null;
    static oAddNotifySound = null;

    static oEvents = {
        projects_select: "projects:select",
        debug_files_select: "debug_files:select",
    }

    // static fnNotifyAdd(oLast={})
    // {
    //     this.oAddNotifySound.muted = false;
    //     this.oAddNotifySound.play();

    //     var oNotification = new Notification(
    //         "Добавлена ссылка", {
    //         tag : "ache-mail",
    //         body : oLast.name,
    //         icon : ""
    //     });
    // }

    static fnInitComponent()
    {
        // this.oAddNotifySound = document.getElementById('notification');
        this.oEventSource = null; // new EventSource("/sse.php");
    }

    static fnBindEvents()
    {
        // this.oEventSource.addEventListener(
        //     "notify_add", 
        //     (function(event) {
        //         var oLast = JSON.parse(event.data);
        //         this.fnNotifyAdd(oLast);
        //     }).bind(this)
        // );

        $(document).on(this.oEvents.projects_select, ((oEvent, oNode) => {
            this._oSelected = oNode;
            if (this.oEventSource) {
                this.oEventSource.close();
            }
            this.oEventSource = new EventSource(`/sse.php?project_id=${oNode.id}`);

            this.oEventSource.addEventListener(
                "files_count", 
                (function(event) {
                    var oFiles = JSON.parse(event.data);
                    var bChanged = (oFiles.db_files_count < oFiles.files_count);

                    $("#project-files-info")
                        .html(`<span class="dfc">${oFiles.db_files_count}</span>/<span class="fc">${oFiles.files_count}</span>`);
                }).bind(this)
            );
        }).bind(this))
    }

    static fnPrepare()
    {
        this.fnInitComponent();
        this.fnBindEvents();
    }
}

