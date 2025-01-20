// -------------------------------------------------------------------------
// init variable
var TELEPHONY_EXTENSION = '';
var TELEPHONY_LEVEL = '';
var TELEPHONY_NETWORK_STATE = '';
var TELEPHONY_CURRENT_STATUS = '';
var TELEPHONY_CURRENT_STATUS_REASON = '';
var TELEPHONY_CALLER_ID = '';
var TELEPHONY_RECORDING_ID = '';
var TELEPHONY_PHONE_NUMBER = '';
var TELEPHONY_CUSTOMER_DATA_ARR = [];
var TELEPHONY_ACCOUNT_HANDLING = '';

const config = {
    ecentrix: {
        agentAuthorizationUrl: undefined,
        applicationUrl: undefined,
        clientId: undefined,
        authKey: undefined
    },
    parameter: {
        debug: undefined,
        iceMethod: undefined,
        iceSecure: undefined,
        autoAccept: undefined,
        autoAcceptTime: undefined
    },
    network: {
        pingInterval: undefined,
        lowLatency: undefined,
        highLatency: undefined,
        lowJitter: undefined,
        highJitter: undefined
    },
    visual: {
        disableAudioUi: undefined,
        disableNetworkUi: undefined
    },
    loaded: false
};
const agentId = AGENT_ID;
var level = LEVEL_GROUP;
var apilevel = 'agent';
if (level == 'TELECOLL' || level == 'FIELD_COLL') {
    apilevel = 'agent';
} else if (level != 'TELECOLL') {
    apilevel = 'supervisor';
}

// -------------------------------------------------------------------------
/**
 * Login
 */
function login() {
    if (!config.loaded) {
        console.error("configuration is not loaded, yet");
        return;
    }
    logout();
    disable("login");
    getToken(config.ecentrix.clientId, config.ecentrix.authKey, agentId)
        .then((token) => {

            const param = getOptionalParameter();
            const url = config.ecentrix.applicationUrl +
                "/" + apilevel + "/id/" + agentId +
                "/token/" + token +
                "?" + param;
            // alert(url);
            console.log(url);
            const frame = document.createElement("iframe");
            frame.src = url;
            frame.id = "ecentrix";
            frame.name = "ecentrix";
            frame.allow = "microphone; camera; encrypted-media";
            frame.className = "frame";
            frame.style.cssText = "height: 54px;width: 100%;";

            document.getElementById('barTelephony').appendChild(frame);
        })
        .catch((error) => {
            console.error("cannot login due to: %s", error);
        });
}
/**
 * Logout
 */
function logout() {
    const frames = document.querySelectorAll("iframe");
    frames.forEach((frame) => {
        frame.parentNode.removeChild(frame);
    });
    disable("idle", "acw", "aux", "outbound", "logout");
    enable("login");
    network(undefined);
}
/**
 * Idle
 */
function idle() {
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "idle"
        }, "*");
    }
}

function autoIn() {
    TELEPHONY_CALLER_ID = '';
    TELEPHONY_RECORDING_ID = '';
    TELEPHONY_PHONE_NUMBER = '';
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "idle"
        }, "*");
    }
}


/**
 * ACW
 */
function acw() {
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "acw"
        }, "*");
    }
}
/**
 * AUX
 * @param reason
 */
function aux(reason) {
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "aux",
            reason
        }, "*");
    }
}
/**
 * Outbound
 */
function outbound() {
    const frame = getFrame();
    TELEPHONY_ACCOUNT_HANDLING = '';

    if (frame) {
        frame.postMessage({
            action: "outbound"
        }, "*");
    }
}

function stopSpying() {
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "stopSpying"
        }, "*");
    }
}


function startSpying(agentId, mode) {
    const frame = getFrame();
    outbound();
    setTimeout(() => {

        if (frame) {
            frame.postMessage({
                action: "startSpying",
                agentId: agentId,
                mode: mode
            }, "*");
        }
    }, 800);
}



function replacePrefix(phoneNumber) {
    // Mengecek apakah nomor telepon dimulai dengan +62
    if (phoneNumber.startsWith("+62")) {
        // Mengganti +62 dengan 08
        return phoneNumber.replace("+62", "62");
    }
    return phoneNumber; // Jika tidak ada +62, kembalikan nomor asli
}



function originateCall(phoneNumber, data, contract_number = '') {
    const frame = getFrame();
    outbound();
    TELEPHONY_CALLER_ID = '';
    TELEPHONY_RECORDING_ID = '';
    TELEPHONY_PHONE_NUMBER = '';
    TELEPHONY_ACCOUNT_HANDLING = contract_number;

    setTimeout(() => {
        if (frame) {
            frame.postMessage({
                action: "originateCall",
                phoneNumber: replacePrefix(phoneNumber),
                customerData: data
            }, "*");
        }
    }, 800);
}

function disconnectCall() {
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "disconnectCall"
        }, "*");
    }
}
/**
 * Accept call
 */
function acceptCall() {
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "acceptCall"
        }, "*");
    }
}
/**
 * Decline call
 */
function declineCall() {
    const frame = getFrame();
    if (frame) {
        frame.postMessage({
            action: "declineCall"
        }, "*");
    }
}

// -------------------------------------------------------------------------
/**
 * Retrieves frame
 */
function getFrame() {
    const frame = document.getElementById("ecentrix");
    if (frame) {
        return frame.contentWindow;
    }
    return undefined;
}
/**
 * Composes optional parameter
 */
function getOptionalParameter() {
    let param = {};
    ["parameter", "network", "visual"].forEach((groupKey) => {
        const group = config[groupKey];
        if (group) {
            Object.keys(group).forEach((key) => {
                if (group[key]) {
                    const snakeCaseKey = key.trim().split(/(?=[A-Z])/).join("_").toLowerCase();
                    param[snakeCaseKey] = group[key];
                }
            });
        }
    });
    return Object.keys(param).map((key) => key + "=" + param[key]).join("&");
}
/**
 * Retrieves token object from authorizer
 * @param clientId
 * @param authKey
 * @param agentId
 */
function getToken(clientId, authKey, agentId) {
    return new Promise((resolve, reject) => {
        if (!config.loaded) {
            reject("configuration is not loaded, yet");
        }

        var content = {
            clientId,
            authKey,
            agentId
        };

        if (level == 'SUPERVISOR') {
            content = {
                clientId,
                authKey,
                "supervisor_id": agentId
            };
        }

        const url = config.ecentrix.agentAuthorizationUrl;

        $.post(
            url,
            JSON.stringify(toSnakeCase(content)),
            function (response) {
                if (response.error) {
                    reject(response.content);
                } else {
                    const json = toCamelCase(response);
                    resolve(json.accessToken);
                }
            },
            "json"
        );
    });
}
/**
 * Retrieves call center configuration
 */
function getCallCenterConfiguration(levelparam) {

    let levelx = levelparam.toUpperCase();
    let apilevelx = levelparam.toLowerCase();
    if (levelx == 'AGENT') {
        apilevelx = 'agent';
        apilevel = apilevelx;
        level = levelx;
    } else if (levelx != 'AGENT') {
        apilevelx = 'supervisor';
        apilevel = apilevelx;
        level = levelx;
    }

    const regex = /\/(\w+)\/(\w+)/;
    let url = "index.php/Ecentrix8/getCallCenterConfiguration";
    if (levelx == 'AGENT') {
        url = "index.php/Ecentrix8/getCallCenterConfiguration";
    } else if (levelx == 'SUPERVISOR') {
        url = "index.php/Ecentrix8/getCallCenterConfigurationSupervisor";
    }
    return new Promise((resolve) => {
        $.ajax({
            dataType: "json",
            url: url,
            success: function (json) {
                // console.log(json);
                Object.keys(json).forEach((key) => {
                    const match = key.match(regex);
                    if (match.length === 3) {
                        config[match[1]][match[2]] = json[key];
                    }
                });
                config.loaded = true;
                enable("login");
                resolve(true);
            }
        });
    });
}
/**
 * Transforms specified object into object with camel-case key
 */
function toCamelCase(obj) {
    if (typeof obj === "object") {
        const result = {};
        Object.keys(obj).forEach((key) => {
            const camelCaseKey = key.toLowerCase()
                .replace(/[^a-zA-Z0-9]+(.)/g, (m, chr) => chr.toUpperCase());
            result[camelCaseKey] = toSnakeCase(obj[key]);
        });
        return result;
    }
    return obj;
}
/**
 * Transforms specified object into object with snake-case key
 */
function toSnakeCase(obj) {
    if (typeof obj === "object") {
        const result = {};
        Object.keys(obj).forEach((key) => {
            const snakeCaseKey = key.trim().split(/(?=[A-Z])/).join("_").toLowerCase();
            result[snakeCaseKey] = toSnakeCase(obj[key]);
        });
        return result;
    }
    return obj;
}
/**
 * Enables element based on specified IDs
 */
function enable() {
    if (arguments.length === 0) {
        return;
    }
    Array.from(arguments).forEach((id) => {
        const element = document.getElementById(id);
        if (element) {
            element.removeAttribute("disabled");
        }
    });
}
/**
 * Disables element based on specified IDs
 */
function disable() {
    if (arguments.length === 0) {
        return;
    }
    Array.from(arguments).forEach((id) => {
        const element = document.getElementById(id);
        if (element) {
            element.setAttribute("disabled", "true");
        }
    });
}
/**
 * Shows element based on specified IDs
 */
function show() {
    if (arguments.length === 0) {
        return;
    }
    Array.from(arguments).forEach((id) => {
        const element = document.getElementById(id);
        if (element) {
            element.style.display = "block";
        }
    });
}
/**
 * Hides element based on specified IDs
 */
function hide() {
    if (arguments.length === 0) {
        return;
    }
    Array.from(arguments).forEach((id) => {
        const element = document.getElementById(id);
        if (element) {
            element.style.display = "none";
        }
    });
}
/**
 * Sets information
 */
function information(str) {
    const element = document.getElementById("information");
    if (element) {
        element.innerText = str ? str : "";
    }
}
/**
 * Sets network information
 */
function network(state, latency, jitter) {

    const element = document.getElementById("network");
    if (element) {
        if (state) {
            if (latency !== undefined && jitter !== undefined) {
                element.innerText = "network (state=" + state + ", latency=" + latency + "ms, jitter=" + jitter + "ms)";
            } else {
                element.innerText = "network (state=" + state + ")";
            }


        } else {
            element.innerText = "";
        }
    }

    if (state == 'GOOD') {
        $("#TELEPHONY_NETWORK_STATE").attr('style', 'color:green');
    }
    else if (state == 'BAD') {
        $("#TELEPHONY_NETWORK_STATE").attr('style', 'color:red');
    } else {
        $("#TELEPHONY_NETWORK_STATE").removeAttr('style');
        $("#TELEPHONY_CURRENT_STATUS").hide('slow');
        $("#TELEPHONY_CURRENT_STATUS_AGENT_SCREEN").hide('slow');
        // $("#TELEPHONY_CURRENT_STATUS_REASON").hide('slow');

    }
}

function updateAccountCodeSessionLog(call_id, contract_number) {
    url = "index.php/Ecentrix8/updateAccountCodeSessionLog";
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: { call_id: call_id, contract_number: contract_number },
        success: function (msg) {
            // console.log(json);
        }
    });
}
// -------------------------------------------------------------------------
// on load
// window.addEventListener("load", getCallCenterConfiguration);
// listen for messages
window.addEventListener("message", function (e) {
    // IMPORTANT: validation for security purpose
    // console.log('eeee', e);
    if (!e.origin.endsWith(".net:8080")) {
        // return
        return;
    }
    // message
    const message = e.data;
    // log
    // console.log("message baru", message);


    // check message event
    try {
        if (message.event.startsWith("agent-")) {
            // console.log('1');
            // check message result
            TELEPHONY_LEVEL = 'AGENT';
            $("#TELEPHONY_LEVEL").html(TELEPHONY_LEVEL);

            if (message.result === "SUCCESS") {
                // check message once again
                // console.log("success");
                if (message.event === "agent-login") {
                    enable("idle", "acw", "aux", "outbound", "logout");
                    TELEPHONY_CURRENT_STATUS = 'LOGIN';

                    try {
                        TELEPHONY_EXTENSION = message.extension;
                        $("#TELEPHONY_EXTENSION").hide('slow');
                        $("#TELEPHONY_EXTENSION").html(TELEPHONY_EXTENSION);
                        $("#TELEPHONY_EXTENSION").show('slow');
                    } catch (error) {

                    }

                } else if (message.event === "agent-idle") {
                    disable("idle");
                    enable("acw", "aux", "outbound");
                    TELEPHONY_CURRENT_STATUS = 'IDLE';
                    console.log("IDLE");
                } else if (message.event === "agent-acw") {
                    disable("acw");
                    enable("idle", "aux", "outbound");
                    TELEPHONY_CURRENT_STATUS = 'ACW';
                    console.log("ACW");
                } else if (message.event === "agent-aux") {
                    disable("aux");
                    enable("idle", "acw", "outbound");
                    TELEPHONY_CURRENT_STATUS = 'AUX';
                    if (message.reason) {
                        TELEPHONY_CURRENT_STATUS_REASON = message.reason;
                    }



                } else if (message.event === "agent-outbound") {
                    disable("outbound");
                    enable("idle", "acw", "aux");
                    TELEPHONY_CURRENT_STATUS = 'OUTBOUND';
                    try {
                        $("#btnStopGetAccount").click();
                    } catch (error) {
                        console.log('error', error);
                    }
                } else if (message.event === "agent-on-hook") {
                    TELEPHONY_CURRENT_STATUS = 'ONHOOK';
                } else if (message.event === "agent-talking") {
                    TELEPHONY_CURRENT_STATUS = 'TALKING';
                }
                else if (message.event === "ring") {
                    const {
                        phoneNumber,
                        customerData
                    } = message;
                    information("incoming call (phoneNumber=" + phoneNumber + ", customerData=" + customerData + ")");
                    show("confirmation");
                    TELEPHONY_CURRENT_STATUS = 'RING';
                } else if (message.event === "connected" || message.event === "accepted") {
                    const {
                        phoneNumber,
                        customerData
                    } = message;
                    information("call connected (phoneNumber=" + phoneNumber + ", customerData=" + customerData + ")");
                    hide("confirmation");
                    TELEPHONY_CURRENT_STATUS = 'ACCEPTED';
                    TELEPHONY_PHONE_NUMBER = phoneNumber;
                    TELEPHONY_CALLER_ID = message.callId;
                    TELEPHONY_RECORDING_ID = message.recordingId;
                    console.log('customerData', customerData);
                    console.log('phoneNumber', phoneNumber);
                    console.log('message', message);

                    if (message.type != 'OUTBOUND') {
                        let customerDataToJson = customerData.split('&');

                        TELEPHONY_CUSTOMER_DATA_ARR = [];
                        $.each(customerDataToJson, (i, val) => {

                            if (val.includes('=')) {
                                let arr = val.split('=');

                                TELEPHONY_CUSTOMER_DATA_ARR[arr[0]] = arr[1];
                            }

                        })
                        if (typeof predialAction === "function") {
                            predialAction(message.event.callId, phoneNumber, TELEPHONY_CUSTOMER_DATA_ARR, message.event.recordingId);
                        }
                    }




                } else if (e.data.event === "disconnected") {
                    console.log('disconnected...');
                    updateAccountCodeSessionLog(TELEPHONY_CALLER_ID, TELEPHONY_ACCOUNT_HANDLING);

                    information("call disconnected");
                    hide("confirmation");
                    setTimeout(() => {
                        information(undefined);
                    }, 3000);
                    TELEPHONY_CURRENT_STATUS = 'DISCONNECTED';
                    document.getElementById("btn-dial-Handphone").innerHTML = "Dial";
                    $("#btn-dial-Handphone").val("Dial").removeClass("btn-danger").addClass("btn-success");
                    // TELEPHONY_ACCOUNT_HANDLING = '';
                    // TELEPHONY_CALLER_ID = '';
                } else {
                    console.log("elseee....");
                }


                $("#TELEPHONY_CURRENT_STATUS").hide('slow');
                $("#TELEPHONY_CURRENT_STATUS").html(TELEPHONY_CURRENT_STATUS);
                $("#TELEPHONY_CURRENT_STATUS").show('slow');

                $("#TELEPHONY_CURRENT_STATUS_AGENT_SCREEN").hide('slow');
                $("#TELEPHONY_CURRENT_STATUS_AGENT_SCREEN").html(TELEPHONY_CURRENT_STATUS);
                $("#TELEPHONY_CURRENT_STATUS_AGENT_SCREEN").show('slow');
                if (message.reason) {
                    TELEPHONY_CURRENT_STATUS_REASON = message.reason;
                    $("#TELEPHONY_CURRENT_STATUS_REASON").html(TELEPHONY_CURRENT_STATUS_REASON).show('slow');
                    $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html(TELEPHONY_CURRENT_STATUS_REASON).show('slow');
                } else {
                    TELEPHONY_CURRENT_STATUS_REASON = '';
                    $("#TELEPHONY_CURRENT_STATUS_REASON").hide('slow');
                    $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").hide('slow');
                    setTimeout(() => {
                        $("#TELEPHONY_CURRENT_STATUS_REASON").html('');
                        $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html('');
                    }, 1000);
                }
            } else {
                $("#TELEPHONY_EXTENSION").html('<i>failed</i>');
                if (message.event === "agent-login") {
                    if (message.reason) {
                        TELEPHONY_CURRENT_STATUS_REASON = message.reason;
                        $("#TELEPHONY_CURRENT_STATUS_REASON").html(TELEPHONY_CURRENT_STATUS_REASON).show();
                        $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html(TELEPHONY_CURRENT_STATUS_REASON).show();
                    } else {
                        TELEPHONY_CURRENT_STATUS_REASON = '';
                        setTimeout(() => {
                            $("#TELEPHONY_CURRENT_STATUS_REASON").html('');
                            $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html('');
                        }, 1000);
                    }
                }



            }

        }
        else if (message.event.startsWith("supervisor-")) {

            TELEPHONY_LEVEL = 'SUPERVISOR';
            $("#TELEPHONY_LEVEL").html('SUPERVISOR/ADMIN');
            // check message result
            if (message.result === "SUCCESS") {
                // check message once again
                if (message.event === "supervisor-login") {
                    enable("idle", "acw", "aux", "outbound", "logout");
                    TELEPHONY_CURRENT_STATUS = 'LOGIN';

                    try {
                        TELEPHONY_EXTENSION = message.extension;
                        $("#TELEPHONY_EXTENSION").hide('slow');
                        $("#TELEPHONY_EXTENSION").html(TELEPHONY_EXTENSION);
                        $("#TELEPHONY_EXTENSION").show('slow');
                    } catch (error) {

                    }

                } else if (message.event === "supervisor-idle") {
                    disable("idle");
                    enable("acw", "aux", "outbound");
                    TELEPHONY_CURRENT_STATUS = 'IDLE';

                } else if (message.event === "supervisor-acw") {
                    disable("acw");
                    enable("idle", "aux", "outbound");
                    TELEPHONY_CURRENT_STATUS = 'ACW';

                } else if (message.event === "supervisor-aux") {
                    disable("aux");
                    enable("idle", "acw", "outbound");
                    TELEPHONY_CURRENT_STATUS = 'AUX';

                    if (message.reason) {
                        TELEPHONY_CURRENT_STATUS_REASON = message.reason;
                    }

                } else if (message.event === "supervisor-outbound") {
                    disable("outbound");
                    enable("idle", "acw", "aux");
                    TELEPHONY_CURRENT_STATUS = 'OUTBOUND';

                } else if (message.event === "supervisor-on-hook") {
                    TELEPHONY_CURRENT_STATUS = 'ONHOOK';
                } else if (message.event === "supervisor-talking") {
                    TELEPHONY_CURRENT_STATUS = 'TALKING';
                }

                $("#TELEPHONY_CURRENT_STATUS").hide('slow');
                $("#TELEPHONY_CURRENT_STATUS").html(TELEPHONY_CURRENT_STATUS);
                $("#TELEPHONY_CURRENT_STATUS").show('slow');

                $("#TELEPHONY_CURRENT_STATUS_AGENT_SCREEN").hide('slow');
                $("#TELEPHONY_CURRENT_STATUS_AGENT_SCREEN").html(TELEPHONY_CURRENT_STATUS);
                $("#TELEPHONY_CURRENT_STATUS_AGENT_SCREEN").show('slow');

                if (message.reason) {
                    TELEPHONY_CURRENT_STATUS_REASON = message.reason;
                    $("#TELEPHONY_CURRENT_STATUS_REASON").html(TELEPHONY_CURRENT_STATUS_REASON).show('slow');
                    $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html(TELEPHONY_CURRENT_STATUS_REASON).show('slow');
                } else {
                    TELEPHONY_CURRENT_STATUS_REASON = '';
                    $("#TELEPHONY_CURRENT_STATUS_REASON").hide('slow');
                    $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").hide('slow');
                    setTimeout(() => {
                        $("#TELEPHONY_CURRENT_STATUS_REASON").html('');
                        $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html('');
                    }, 1000);
                }
            } else {
                $("#TELEPHONY_EXTENSION").html('<i>failed</i>');
                if (message.event === "supervisor-login") {
                    if (message.reason) {
                        TELEPHONY_CURRENT_STATUS_REASON = message.reason;
                        $("#TELEPHONY_CURRENT_STATUS_REASON").html(TELEPHONY_CURRENT_STATUS_REASON).show();
                        $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html(TELEPHONY_CURRENT_STATUS_REASON).show();
                    } else {
                        TELEPHONY_CURRENT_STATUS_REASON = '';
                        setTimeout(() => {
                            $("#TELEPHONY_CURRENT_STATUS_REASON").html('');
                            $("#TELEPHONY_CURRENT_STATUS_REASON_AGENT_SCREEN").html('');
                        }, 1000);
                    }
                }

            }

        }
        else if (message.event === "websocket-opened") {
            network("connected");
        } else if (message.event == "websocket-heartbeat") {
            network(message.networkState, message.latency, message.jitter);
        }
        else if (message.event === "websocket-closed") {
            network(message.networkState, message.latency, message.jitter);
        }
        else if (message.event === "ring") {
            const {
                phoneNumber,
                customerData
            } = message;
            information("incoming call (phoneNumber=" + phoneNumber + ", customerData=" + customerData + ")");
            show("confirmation");
            TELEPHONY_CURRENT_STATUS = 'RING';
        } else if (message.event === "connected" || message.event === "accepted") {
            const {
                phoneNumber,
                customerData
            } = message;
            information("call connected (phoneNumber=" + phoneNumber + ", customerData=" + customerData + ")");
            hide("confirmation");
            TELEPHONY_CURRENT_STATUS = 'ACCEPTED';
            TELEPHONY_PHONE_NUMBER = phoneNumber;
            TELEPHONY_CALLER_ID = message.callId;
            TELEPHONY_RECORDING_ID = message.recordingId;

            if (message.type != 'OUTBOUND') {
                let customerDataToJson = customerData.split('&');

                TELEPHONY_CUSTOMER_DATA_ARR = [];
                $.each(customerDataToJson, (i, val) => {

                    if (val.includes('=')) {
                        let arr = val.split('=');

                        TELEPHONY_CUSTOMER_DATA_ARR[arr[0]] = arr[1];
                    }

                })
                if (typeof predialAction === "function") {
                    predialAction(message.event.callId, phoneNumber, TELEPHONY_CUSTOMER_DATA_ARR, message.event.recordingId);
                }
            }




        } else if (e.data.event === "disconnected") {
            console.log('disconnected...');
            updateAccountCodeSessionLog(TELEPHONY_CALLER_ID, TELEPHONY_ACCOUNT_HANDLING);

            information("call disconnected");
            hide("confirmation");
            setTimeout(() => {
                information(undefined);
            }, 3000);
            TELEPHONY_CURRENT_STATUS = 'DISCONNECTED';
            document.getElementById("btn-dial-Handphone").innerHTML = "Dial";
            $("#btn-dial-Handphone").val("Dial").removeClass("btn-danger").addClass("btn-success");
            // TELEPHONY_ACCOUNT_HANDLING = '';
            // TELEPHONY_CALLER_ID = '';
        } else {
            console.log("elseee....");
        }
    } catch (error) {
        console.log('error', error);
    }
});




