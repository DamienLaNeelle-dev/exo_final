import React from "react";
import { createRoot } from "react-dom/client";
import Users from "./components/Users";

import "./css/app.css";

const container = document.getElementById("app");
const root = createRoot(container);
root.render(<Users />);
