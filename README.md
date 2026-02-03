<p align="center">
  <a href="https://app.yourditchdoc.com" target="_blank" rel="noopener noreferrer">
    <strong>🚀 Click here to launch the DFW ER Navigator Pro Tool</strong>
  </a>
</p>

"Hi there! By day (and often by night), I’m a paramedic working on the front lines. When I’m not on the road, I’m at my desk diving into the world of computer science. It’s my favorite way to decompress after a long shift.

> Your support helps keep me caffeinated for those 24-hour shifts and contributes to my learning journey in tech. Whether it's a 'thank you' for my service or just a shared love for clean code, you can <a href="https://buymeacoffee.com/yourditchdoc" target="_blank" rel="noopener noreferrer">**support the project here**↗️</a>.

Smart Ambulance Routing for the Dallas-Fort Worth EMS Community.
### 🚑 System Architecture: How It Works

The **DFW ER Navigator Pro** operates through a seamless loop between your data, your administration panel, and the end-user interface.

| Component | Role | File | Function |
| :--- | :--- | :--- | :--- |
| **The Brain** | **Data Storage** | `hospitals.json` | Stores hospital coordinates, specialty levels (Trauma/Stroke), and secure door codes. |
| **The Interface** | **User View** | `index.php` | Detects the medic's GPS, calculates distance to all ERs, and filters results based on patient needs. |
| **The Control** | **Admin Panel** | `admin.php` | Allows authorized users to update door codes or hospital capabilities without touching the code. |

---

### 🔄 The Operational Flow

1.  **Data Management:** An admin logs into `admin.php`. When a hospital's "EMS Room Code" changes, the admin updates it. This writes the new info directly into `hospitals.json`.
2.  **Location Awareness:** When a medic opens the app, `index.php` uses the browser's GPS to find their exact Latitude and Longitude.
3.  **Real-Time Logic:** The app runs the **Haversine Formula** against the data in `hospitals.json` to sort every hospital from "Closest" to "Furthest."
4.  **Specialty Filtering:** The medic selects a filter (e.g., "Level 1 Trauma"). The app instantly hides all facilities that do not have the "TRAUMA" tag and "I" level in the JSON file.
5.  **Navigation & Contact:** The medic taps the hospital card to immediately trigger a phone call to the ER or launch Google Maps for turn-by-turn routing.

   
<p align="center">
  <a href="https://app.yourditchdoc.com" target="_blank" rel="noopener noreferrer">
    <strong>🚀 Click here to launch the DFW ER Navigator Pro Tool</strong>
  </a>
</p>

🚑 The Mission
In emergency medicine, the "closest" hospital isn't always the appropriate hospital. DFW ER Navigator Pro is a mission-critical web tool designed for paramedics and EMTs to quickly identify the nearest facility capable of handling specific patient needs—whether it's a Level 1 Trauma Center, a Comprehensive Stroke Center (CSC), or a specialized Burn Unit.

✨ Key Features
Live GPS Sorting: Automatically calculates real-time distance from your current location to every ER in the DFW metroplex.

Specialty Filtering: Instantly filter facilities by capabilities:

Trauma: Level I & II Centers.

Stroke: Primary (PSC) and Comprehensive (CSC) designations.

Cardiac: STEMI-capable facilities.

Specialized: Pediatrics (PEDS), Hyperbaric Oxygen (HBOT), and Burn Centers.

Secure Logistics: A protected "EMS Room" and "ER Door" code database to streamline patient handoff (Requires credential verification).

One-Touch Actions: Built-in buttons to trigger immediate phone calls to the ER or launch turn-by-turn navigation via Google Maps.

Community-Driven Updates: Integrated reporting system to keep facility codes and capabilities up-to-date via peer-sourced data.

🛠️ Technology Stack
Frontend: HTML5, CSS3, JavaScript (ES6+)

Mapping: Leaflet.js & OpenStreetMap

Data: JSON-based hospital database with Haversine formula for distance calculations.

❤️ Support Development
I am providing this tool for free to help my fellow medics save time and improve patient outcomes. However, maintaining the server and updating the hospital database takes significant time and resources.

If this tool has helped you on a shift, please consider supporting further development:

[Donate via Buy Me a Coffee] (Insert your link here)

[Support on PayPal] (Insert your link here)

📝 How to Contribute
Report Data Changes: Use the "EDIT" button on any hospital card to submit updated door codes or specialty designations.

Code Contributions: Feel free to fork this repository and submit pull requests for UI improvements or new features.

Disclaimer: This tool is intended for use by trained emergency medical professionals. Always follow your local protocols and medical direction when determining patient destination.
