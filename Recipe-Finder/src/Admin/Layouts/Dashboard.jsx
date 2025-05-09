import DashboardHeader from "../Components/DashboardHeader";
import '../scss/Dashboard.scss';
import DashboardContent from "../Components/DashboardContent";
import DashboardSider from "../Components/DashboardSider";
import { Outlet } from "react-router-dom";

export default function Dashboard() {
  return (
    <>
      <DashboardHeader />
      <div className="content-area">
        <DashboardSider />
          <Outlet />
      </div>
    </>
  )
}