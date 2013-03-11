<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Fluid Grid with Fluid Columns</title>
        <link type="text/css" rel="stylesheet" href="fluid_grid_test.css" />
    </head>
    <body>
        <div id="mainContainer" class="full-width">
            <div id="mainContent" class="full-width">
                <div id="logo" class="grid-container">
                    <div class="grid-12">Logo</div>
                </div>
                <div id="filters" class="full-width">
                    <div id="socialDesc" class="grid-container">
                        <div class="grid-12">Text goes here</div>
                    </div>
                    <div id="socialBtns" class="grid-container">
                        <div class="grid-6">Button 1</div>
                        <div class="grid-6">Button 2</div>
                    </div>
                    <div id="deviderTxt" class="grid-container">
                        <div class="grid-12">--- OR ---</div>
                    </div>
                    <div id="search" class="grid-container">
                        <div class="grid-12">Search</div>
                    </div>
                </div>
                <div id="results" class="full-width">
                    <div class="grid-container">
                        <div class="grid-12">Today</div>
                        <div class="full-width">
                            <div class="grid-3">7:30pm</div>
                            <div class="grid-7">The Greatest Band in the World</div>
                            <div class="grid-2">+Cal</div>
                        </div>
                    </div>
                    <div class="grid-container">
                        <div class="grid-12">Tomorrow</div>
                        <div class="full-width">
                            <div class="grid-3">8:00pm</div>
                            <div class="grid-7">The 2nd Greatest Band in the World</div>
                            <div class="grid-2">+Cal</div>
                        </div>
                        <div class="full-width">
                            <div class="grid-3">8:30pm</div>
                            <div class="grid-7">The 3rdGreatest Band in the World</div>
                            <div class="grid-2">+Cal</div>
                        </div>
                    </div>
                </div>
                <div class="push"></div>
            </div>
            <div id="footer" class="full-width">
                Footer
            </div>
        </div>
    </body>
</html>
