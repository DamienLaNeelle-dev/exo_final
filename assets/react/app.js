import React from "react";
import { createRoot } from "react-dom/client";
import Users from "./components/Users";

const container = document.getElementById("app");
const root = createRoot(container);
root.render(
  <React.StrictMode>
    <Users />
  </React.StrictMode>
);
