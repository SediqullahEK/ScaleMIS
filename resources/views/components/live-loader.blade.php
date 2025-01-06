  <head>
      <style>
          .loading2 {
              z-index: 20;
              position: fixed;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              margin: auto;
              width: 100%;
              height: 100%;

              background-color: rgba(0, 0, 0, 0.4);
          }

          .loading-content {
              position: fixed;
              border: 16px solid #083d6a;
              /* Light grey */
              border-top: 16px solid orange;
              /* Blue */
              border-radius: 50%;
              width: 50px;
              height: 50px;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              margin: auto;
              animation: spin 2s linear infinite;
          }

          @keyframes spin {
              0% {
                  transform: rotate(0deg);
              }

              100% {
                  transform: rotate(360deg);
              }
          }
      </style>
  </head>

  <section id="loading2" class="loading2">
      <div id="loading-content" class="loading-content"></div>
  </section>
