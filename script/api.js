/* exported gapiLoaded */
/* exported gisLoaded */
/* exported handleAuthClick */
/* exported handleSignoutClick */

// TODO(developer): Set to client ID and API key from the Developer Console
const CLIENT_ID =
  "367219087048-ejg4kq53ni82ch0u9ce9i8fvdqj8cpt7.apps.googleusercontent.com";
const API_KEY = "AIzaSyAKV9sCEDIUEKuz0IqHzo3sPgE_0hVBz2Y";

// Discovery doc URL for APIs used by the quickstart
const DISCOVERY_DOC =
  "https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest";

// Authorization scopes required by the API; multiple scopes can be
// included, separated by spaces.
const SCOPES = "https://www.googleapis.com/auth/calendar";

let tokenClient;
let gapiInited = false;
let gisInited = false;

document.getElementById("authorize_button").style.visibility = "hidden";
document.getElementById("signout_button").style.visibility = "hidden";

/**
 * Callback after api.js is loaded.
 */
function gapiLoaded() {
  gapi.load("client", initializeGapiClient);
}

/**
 * Callback after the API client is loaded. Loads the
 * discovery doc to initialize the API.
 */
async function initializeGapiClient() {
  await gapi.client.init({
    apiKey: API_KEY,
    discoveryDocs: [DISCOVERY_DOC],
  });
  gapiInited = true;
  maybeEnableButtons();
}

/**
 * Callback after Google Identity Services are loaded.
 */
function gisLoaded() {
  tokenClient = google.accounts.oauth2.initTokenClient({
    client_id: CLIENT_ID,
    scope: SCOPES,
    callback: "", // defined later
  });
  gisInited = true;
  maybeEnableButtons();
}

/**
 * Enables user interaction after all libraries are loaded.
 */
function maybeEnableButtons() {
  if (gapiInited && gisInited) {
    document.getElementById("authorize_button").style.visibility = "visible";
  }
}

/**
 *  Sign in the user upon button click.
 */
function handleAuthClick() {
  tokenClient.callback = async (resp) => {
    if (resp.error !== undefined) {
      throw resp;
    }
    document.getElementById("signout_button").style.visibility = "visible";
    document.getElementById("authorize_button").innerText = "Refresh";
    await listUpcomingEvents();
  };

  if (gapi.client.getToken() === null) {
    // Prompt the user to select a Google Account and ask for consent to share their data
    // when establishing a new session.
    tokenClient.requestAccessToken({ prompt: "consent" });
  } else {
    // Skip display of account chooser and consent dialog for an existing session.
    tokenClient.requestAccessToken({ prompt: "" });
  }
}

/**
 *  Sign out the user upon button click.
 */
function handleSignoutClick() {
  const token = gapi.client.getToken();
  if (token !== null) {
    google.accounts.oauth2.revoke(token.access_token);
    gapi.client.setToken("");
    document.getElementById("content").innerText = "";
    document.getElementById("authorize_button").innerText = "Authorize";
    document.getElementById("signout_button").style.visibility = "hidden";
  }
}

/**
 * Print the summary and start datetime/date of the next ten events in
 * the authorized user's calendar. If no events are found an
 * appropriate message is printed.
 */
async function listUpcomingEvents() {
  let response;
  try {
    const request = {
      calendarId: "primary",
      timeMin: new Date().toISOString(),
      showDeleted: false,
      singleEvents: true,
      maxResults: 10,
      orderBy: "startTime",
    };
    response = await gapi.client.calendar.events.list(request);
  } catch (err) {
    document.getElementById("content").innerText = err.message;
    return;
  }

  const events = response.result.items;
  if (!events || events.length == 0) {
    document.getElementById("content").innerText = "No events found.";
    return;
  }
  // Flatten to string to display
  const output = events.reduce(
    (str, event) =>
      `${str}${event.summary} (${event.start.dateTime || event.start.date})\n`,
    "Events:\n"
  );
  document.getElementById("content").innerText = output;
}
// ccccccc

let events = []; // To store fetched events

async function listUpcomingEvents() {
  try {
    const response = await gapi.client.calendar.events.list({
      calendarId: "primary",
      timeMin: new Date().toISOString(),
      showDeleted: false,
      singleEvents: true,
      maxResults: 10,
      orderBy: "startTime",
    });

    events = response.result.items;
    displayEvents(events);
  } catch (err) {
    console.error("Error fetching events:", err);
    document.getElementById("content").innerText = "";
  }
}

function displayEvents(events) {
  let eventTable = '<table class="table">';
  eventTable +=
    "<thead><tr><th>Event Name</th><th>Date</th><th>Action</th></tr></thead><tbody>";
  events.forEach((event, index) => {
    eventTable += `<tr data-index="${index}"><td>${event.summary}</td><td>${
      event.start.dateTime || event.start.date
    }</td><td><button onclick="editEventDetails(${index})" class="btn btn-sm btn-outline-primary">Edit</button></td></tr>`;
  });
  eventTable += "</tbody></table>";
  document.getElementById("content").innerHTML = eventTable;
}

function editEventDetails(index) {
  const selectedEvent = events[index];
  const editForm = document.getElementById("editForm");
  const editEventName = document.getElementById("editEventName");
  const editEventDescription = document.getElementById("editEventDescription");

  editEventName.value = selectedEvent.summary;
  editEventDescription.value = selectedEvent.description || "";

  editForm.style.display = "block";
  editForm.dataset.index = index; // Store the index of the event being edited
}

function submitEventEdit(event) {
  event.preventDefault();
  const editForm = document.getElementById("editForm");
  const index = editForm.dataset.index;
  const selectedEvent = events[index];
  const editEventName = document.getElementById("editEventName").value;
  const editEventDescription = document.getElementById(
    "editEventDescription"
  ).value;

  selectedEvent.summary = editEventName;
  selectedEvent.description = editEventDescription;

  updateGoogleCalendar(selectedEvent).then(() => {
    editForm.style.display = "none";
    listUpcomingEvents(); // Refresh the event list after editing
  });
}

async function updateGoogleCalendar(updatedEvent) {
  try {
    await gapi.client.calendar.events.update({
      calendarId: "primary",
      eventId: updatedEvent.id,
      resource: updatedEvent,
    });
  } catch (err) {
    console.error("Error updating event:", err);
  }
}

window.onload = function () {
  listUpcomingEvents();
};
