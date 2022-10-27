import React from "react";
import { createRoot } from "react-dom/client";
import Users from "./components/Users";
import Possessions from "./components/Possessions";
import { BrowserRouter, Route, Routes } from "react-router-dom";

const container = document.getElementById("app");
const root = createRoot(container);
root.render(
  <BrowserRouter>
    <Routes>
      <Route path="/" element={<Users />} />
      <Route path="possessions" element={<Possessions />} />
    </Routes>
  </BrowserRouter>
);
