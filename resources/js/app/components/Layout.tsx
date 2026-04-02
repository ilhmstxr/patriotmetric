import { NavLink, Outlet, useLocation } from "react-router";
import logoUrl from "figma:asset/b89aca8b9cc2d0494234bedd13382da054b48ab6.png";

function IconProfile({ active }: { active?: boolean }) {
  return (
    <div className="relative shrink-0 size-[20px]">
      <svg className="absolute block size-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
        <path d="M16.6667 17.5V15.8333C16.6667 14.9493 16.3155 14.1014 15.6903 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66667C5.78261 12.5 4.93476 12.8512 4.30964 13.4763C3.68452 14.1014 3.33333 14.9493 3.33333 15.8333V17.5" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M10 9.16667C11.841 9.16667 13.3333 7.67428 13.3333 5.83333C13.3333 3.99238 11.841 2.5 10 2.5C8.15905 2.5 6.66667 3.99238 6.66667 5.83333C6.66667 7.67428 8.15905 9.16667 10 9.16667Z" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
      </svg>
    </div>
  );
}

function IconForm({ active }: { active?: boolean }) {
  return (
    <div className="relative shrink-0 size-[20px]">
      <svg className="absolute block size-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
        <path d="M11.6667 1.66666H4.16667C3.72464 1.66666 3.30072 1.84226 2.98816 2.15482C2.67559 2.46738 2.5 2.8913 2.5 3.33333V16.6667C2.5 17.1087 2.67559 17.5326 2.98816 17.8452C3.30072 18.1577 3.72464 18.3333 4.16667 18.3333H15.8333C16.2754 18.3333 16.6993 18.1577 17.0118 17.8452C17.3244 17.5326 17.5 17.1087 17.5 16.6667V7.49999L11.6667 1.66666Z" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M11.6667 1.66666V7.49999H17.5" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M8.33333 7.5H6.66667" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M13.3333 10.8333H6.66667" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M13.3333 14.1667H6.66667" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
      </svg>
    </div>
  );
}

function IconHasil({ active }: { active?: boolean }) {
  return (
    <div className="relative shrink-0 size-[20px]">
      <svg className="absolute block size-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
        <path d="M15 16.6667V8.33333" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M10 16.6667V3.33333" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M5 16.6667V11.6667" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
      </svg>
    </div>
  );
}

function IconPanduan({ active }: { active?: boolean }) {
  return (
    <div className="relative shrink-0 size-[20px]">
      <svg className="absolute block size-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
        <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6024 18.3333 10C18.3333 5.39763 14.6024 1.66667 10 1.66667C5.39763 1.66667 1.66667 5.39763 1.66667 10C1.66667 14.6024 5.39763 18.3333 10 18.3333Z" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M7.58334 7.50001C7.58334 6.85968 8.10201 6.34101 8.74234 6.34101H10.8407C11.5363 6.34101 12.1003 6.90501 12.1003 7.60068C12.1003 8.29634 11.5363 8.86034 10.8407 8.86034H10C9.53966 8.86034 9.16667 9.23334 9.16667 9.69368V10.8333" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
        <path d="M10 14.1667H10.0083" stroke={active ? "white" : "#90A1B9"} strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" />
      </svg>
    </div>
  );
}

export function Layout() {
  const location = useLocation();
  
  const getPageTitle = () => {
    switch(location.pathname) {
      case "/dashboard": return "DATA PROFIL";
      case "/dashboard/rubrik": return "FORM RUBRIK";
      case "/dashboard/hasil": return "HASIL PENILAIAN";
      case "/dashboard/panduan": return "PANDUAN PENGGUNA";
      default: return "DASHBOARD";
    }
  };

  const navItems = [
    { to: "/dashboard", icon: IconProfile, label: "Data Profil", end: true },
    { to: "/dashboard/rubrik", icon: IconForm, label: "Form Rubrik" },
    { to: "/dashboard/hasil", icon: IconHasil, label: "Hasil Penilaian" },
    { to: "/dashboard/panduan", icon: IconPanduan, label: "Panduan Pengguna" },
  ];

  return (
    <div className="flex h-screen w-full bg-white font-['Plus_Jakarta_Sans',sans-serif]">
      {/* Sidebar - EXACT styling from Figma */}
      <aside className="w-[280px] bg-white h-full shrink-0 flex flex-col relative shadow-[0_4px_6px_rgba(0,0,0,0.1)] border-r border-[#c89600]">
        {/* Divider line imitating Figma left layout */}
        <div className="absolute bg-[#c89600] h-full right-0 top-0 w-px z-10" />

        <div className="h-[80px] flex items-center justify-center shrink-0 border-b border-[#e2e8f0]">
          <img src={logoUrl} alt="Patriot Metric Logo" className="h-[49px] w-[183px] object-cover" style={{ objectPosition: "center" }} />
        </div>

        <div className="flex flex-col items-center pt-[24px] pb-[32px] shrink-0 border-b border-[#e2e8f0]">
          <div className="w-[72px] h-[72px] bg-[#1b5e20] rounded-full flex items-center justify-center text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] tracking-[0.6px] leading-[32px] mb-[16px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
            UPN
          </div>
          <p className="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[#1d293d] text-[16px] leading-[24px] tracking-[0.4px] whitespace-nowrap">
            Ilham Bintang Herlambang
          </p>
          <p className="font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[#62748e] text-[10px] leading-[14px] text-center mt-[4px] uppercase px-[24px]">
            UNIVERSITAS PEMBANGUNAN NASIONAL VETERAN JAWA TIMUR
          </p>
        </div>

        <div className="flex-1 px-[16px] py-[24px] space-y-[8px]">
          {navItems.map(({ to, label, icon: Icon, end }) => (
            <NavLink
              key={to}
              to={to}
              end={end}
              className={({ isActive }) =>
                `flex items-center gap-[12px] h-[44px] px-[16px] rounded-[8px] transition-all duration-200 ${
                  isActive
                    ? "bg-[#1b5e20] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]"
                    : "bg-transparent hover:bg-slate-50"
                }`
              }
            >
              {({ isActive }) => (
                <>
                  <Icon active={isActive} />
                  <span className={`font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] leading-[20px] ${isActive ? 'text-white' : 'text-[#45556c]'}`}>
                    {label}
                  </span>
                </>
              )}
            </NavLink>
          ))}
        </div>

        <div className="p-[16px] mt-auto shrink-0">
          <button className="flex items-center gap-[12px] h-[44px] px-[16px] w-full rounded-[8px] hover:bg-red-50 transition-colors">
            <div className="relative shrink-0 size-[20px]">
              <svg className="absolute block size-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
                <path d="M7.5 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V4.16667C2.5 3.72464 2.67559 3.30072 2.98816 2.98816C3.30072 2.67559 3.72464 2.5 4.16667 2.5H7.5" stroke="#ef4444" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
                <path d="M13.3333 14.1667L17.5 10L13.3333 5.83333" stroke="#ef4444" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
                <path d="M17.5 10H7.5" stroke="#ef4444" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.66667" />
              </svg>
            </div>
            <span className="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[#ef4444] text-[14px] leading-[20px]">
              Keluar
            </span>
          </button>
        </div>
      </aside>

      {/* Main Content Area */}
      <main className="flex-1 flex flex-col h-full overflow-hidden bg-white">
        {/* Header - EXACT styling from Figma */}
        <header className="bg-white border-b border-[#e2e8f0] h-[80px] px-[32px] flex items-center justify-between shrink-0 w-full z-10 relative">
          <h1 className="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[#1d293d] text-[20px] leading-[28px] tracking-[0.5px] uppercase">
            {getPageTitle()}
          </h1>
        </header>

        <div className="flex-1 overflow-auto bg-white relative">
          <Outlet />
        </div>
      </main>
    </div>
  );
}
