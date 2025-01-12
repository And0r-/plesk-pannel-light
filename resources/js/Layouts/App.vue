<div id="app" class="min-h-screen bg-gray-100 flex">
  <!-- Navigation -->
  <aside class="w-1/5 bg-white shadow-md p-4">
    <nav>
      <ul>
        <li><a href="/domains" class="block p-2">Domains</a></li>
        <li><a href="/settings" class="block p-2">Settings</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-4">
      <!-- Platz für Module -->
      <slot></slot>
    </div>
  </main>
</div>
