    @extends('layouts.app')

    @section('title', 'AI Domain Generator')

    @section('content')
    <div class="container mt-5 flex">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Professional Domain Evaluation Generator</h2>
                        <p class="text-center text-muted mb-4">Generate comprehensive domain reports with market research and valuation analysis</p>
                        
                        <form id="domainForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Domain Name</label>
                                <input id="domain" class="form-control" placeholder="example.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">About the Domain</label>
                                <textarea id="about" class="form-control" rows="3"
                                    placeholder="Describe what this domain will be used for" required></textarea>
                            </div>

                            <button id="generateBtn" class="btn btn-primary w-100">
                                <i class="fas fa-file-contract"></i> Generate Professional Report
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULT -->
        <div id="result" class="mt-5 d-none">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="mb-0">Domain Evaluation Report</h3>
                                <div class="btn-group">
                                    <button id="printBtn" class="btn btn-light btn-sm">
                                        <i class="fas fa-print"></i> Print Report
                                    </button>
                                    <button id="downloadPdfBtn" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- LOADER -->
                            <div id="loader" class="text-center my-5">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                                <h4 class="mt-3">Generating Professional Report</h4>
                                <p class="text-muted">Our AI is conducting market research and preparing your comprehensive domain evaluation...</p>
                            </div>

                            <!-- OUTPUT -->
                            <div id="output" class="d-none">
                                <!-- Table of Contents -->
                                <div id="toc" class="mb-5 p-4 bg-light rounded">
                                    <h5 class="mb-3"><i class="fas fa-list"></i> Report Contents</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="#executive-summary" class="text-decoration-none toc-link">Executive Summary</a></li>
                                        <li class="mb-2"><a href="#market-research" class="text-decoration-none toc-link">Market Research & Analysis</a></li>
                                        <li class="mb-2"><a href="#trademark-research" class="text-decoration-none toc-link">Trademark Research</a></li>
                                        <li class="mb-2"><a href="#keyword-research" class="text-decoration-none toc-link">Keyword Search Volume Research</a></li>
                                        <li class="mb-2"><a href="#competitor-research" class="text-decoration-none toc-link">Competitor Research</a></li>
                                        <li class="mb-2"><a href="#valuation" class="text-decoration-none toc-link">Valuation & Commercial Assessment</a></li>
                                        <li class="mb-2"><a href="#final-summary" class="text-decoration-none toc-link">Final Summary</a></li>
                                    </ul>
                                </div>

                                <!-- Executive Summary Section -->
                                <div id="executive-summary" class="report-section mb-5">
                                    <h4><i class="fas fa-chart-line"></i> Executive Summary</h4>
                                    <h5 id="executiveTitle" class="mb-3"></h5>
                                    <p id="executiveIntro" class="lead"></p>
                                    <div class="mt-4">
                                        <h6>Key Highlights:</h6>
                                        <ul id="keyHighlights" class="list-group list-group-flush"></ul>
                                    </div>
                                </div>

                                <!-- Market Research Section -->
                                <div id="market-research" class="report-section mb-5">
                                    <h4><i class="fas fa-search"></i> Market Research & Analysis</h4>
                                    <div class="mt-4">
                                        <h5>Industry Analysis</h5>
                                        <p id="industryAnalysis" class="bg-light p-3 rounded"></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5>SEO Potential</h5>
                                        <p id="seoPotential" class="bg-light p-3 rounded"></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5>Competitive Landscape</h5>
                                        <p id="competitiveLandscape" class="bg-light p-3 rounded"></p>
                                    </div>
                                </div>

                                <!-- Trademark Research Section -->
                                <div id="trademark-research" class="report-section mb-5">
                                    <h4><i class="fas fa-balance-scale"></i> Trademark Research</h4>
                                    <div class="mt-4">
                                        <h5>Trademark Conflict Analysis</h5>
                                        <p id="trademarkConflicts" class="bg-light p-3 rounded"></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5>Brand Safety Assessment</h5>
                                        <p id="brandSafety" class="bg-light p-3 rounded"></p>
                                    </div>
                                </div>

                                <!-- Keyword Research Section -->
                                <div id="keyword-research" class="report-section mb-5">
                                    <h4><i class="fas fa-chart-bar"></i> Keyword Search Volume Research</h4>
                                    <div class="mt-4">
                                        <h5>Search Volume Analysis</h5>
                                        <p id="searchVolume" class="bg-light p-3 rounded"></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5>CPC & Commercial Value</h5>
                                        <p id="cpcAnalysis" class="bg-light p-3 rounded"></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5>Opportunity Keywords</h5>
                                        <ul id="opportunityKeywords" class="list-group"></ul>
                                    </div>
                                </div>

                                <!-- Competitor Research Section -->
                                <div id="competitor-research" class="report-section mb-5">
                                    <h4><i class="fas fa-users"></i> Competitor Research</h4>
                                    <div class="mt-4">
                                        <h5>Market Analysis</h5>
                                        <p id="competitorAnalysis" class="bg-light p-3 rounded"></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5>Competitor Comparison</h5>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Competitor</th>
                                                        <th>Domain</th>
                                                        <th>Expected Pricing</th>
                                                        <th>Strategy</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="competitorTable">
                                                    <!-- Competitor data will be populated here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Valuation Section -->
                                <div id="valuation" class="report-section mb-5">
                                    <h4><i class="fas fa-dollar-sign"></i> Valuation & Commercial Assessment</h4>
                                    <h5 id="title" class="mb-3"></h5>
                                    
                                    <div class="mb-4">
                                        <h6>Expected Price</h6>
                                        <p id="expectedPrice" class="alert alert-info fs-5 fw-bold"></p>
                                    </div>

                                    <div class="mb-4">
                                        <h6>Description</h6>
                                        <p id="aboutText" class="alert alert-success"></p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Possible Uses for Top Industries</h6>
                                            <ul id="topIndustries" class="list-group mb-4"></ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Recommended Industries</h6>
                                            <ul id="extendedIndustries" class="list-group"></ul>
                                        </div>
                                    </div>
                                </div>
<!-- Final Summary Section -->
<div id="final-summary" class="report-section mb-5">
    <h4><i class="fas fa-check-circle"></i> Final Summary</h4>
    <p id="finalSummaryText" class="bg-light p-4 rounded fs-6"></p>
</div>

                                <!-- Footer -->
                                <div class="border-top pt-3 mt-4 text-center text-muted">
                                    <p>Report generated by BrandIP Domain Intelligence System • {{ date('F j, Y') }}</p>
                                    <p class="small">This report is for informational purposes only and does not constitute financial advice.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script>
    const form = document.getElementById('domainForm');
    const generateBtn = document.getElementById('generateBtn');
    const result = document.getElementById('result');
    const loader = document.getElementById('loader');
    const output = document.getElementById('output');
    const printBtn = document.getElementById('printBtn');

    // Executive Summary Elements
    const executiveTitle = document.getElementById('executiveTitle');
    const executiveIntro = document.getElementById('executiveIntro');
    const keyHighlights = document.getElementById('keyHighlights');

    // Market Research Elements
    const industryAnalysis = document.getElementById('industryAnalysis');
    const seoPotential = document.getElementById('seoPotential');
    const competitiveLandscape = document.getElementById('competitiveLandscape');

    // Trademark Research Elements
    const trademarkConflicts = document.getElementById('trademarkConflicts');
    const brandSafety = document.getElementById('brandSafety');

    // Keyword Research Elements
    const searchVolume = document.getElementById('searchVolume');
    const cpcAnalysis = document.getElementById('cpcAnalysis');
    const opportunityKeywords = document.getElementById('opportunityKeywords');

    // Competitor Research Elements
    const competitorAnalysis = document.getElementById('competitorAnalysis');
    const competitorTable = document.getElementById('competitorTable');

    // Valuation Elements
    const titleEl = document.getElementById('title');
    const aboutText = document.getElementById('aboutText');
    const expectedPrice = document.getElementById('expectedPrice');
    const topIndustries = document.getElementById('topIndustries');
    const extendedIndustries = document.getElementById('extendedIndustries');

    // Final Summary Element
const finalSummaryText = document.getElementById('finalSummaryText');


    /**
     * Direct text display (no typing effect)
     */
    function displayText(el, text) {
        el.textContent = text;
    }

    // Smooth scrolling for table of contents links
    document.querySelectorAll('.toc-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            targetElement.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Print functionality
    printBtn.addEventListener('click', () => {
        window.print();
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        generateBtn.disabled = true;
        generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating Report...';

        result.classList.remove('d-none');
        loader.classList.remove('d-none');
        output.classList.add('d-none');

        // Clear all content
        executiveTitle.textContent = '';
        executiveIntro.textContent = '';
        keyHighlights.innerHTML = '';
        industryAnalysis.textContent = '';
        seoPotential.textContent = '';
        competitiveLandscape.textContent = '';
        trademarkConflicts.textContent = '';
        brandSafety.textContent = '';
        searchVolume.textContent = '';
        cpcAnalysis.textContent = '';
        opportunityKeywords.innerHTML = '';
        competitorAnalysis.textContent = '';
        competitorTable.innerHTML = '';
        titleEl.textContent = '';
        aboutText.textContent = '';
        expectedPrice.textContent = '';
        topIndustries.innerHTML = '';
        extendedIndustries.innerHTML = '';
        finalSummaryText.textContent = '';


        try {
            const response = await fetch('/generate-description', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    domain: domain.value.trim(),
                    about: about.value.trim()
                })
            });

            const json = await response.json();

            if (!json.success) {
                // Hide loader and show error
                loader.classList.add('d-none');
                
                // Display the actual error message from server
                const errorMsg = json.error || json.details || 'AI generation failed';
                alert('Error: ' + errorMsg);
                
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<i class="fas fa-file-contract"></i> Generate Professional Report';
                
                // Hide result section if there was an error
                result.classList.add('d-none');
                return;
            }

            const data = json.data;

            // Show output section immediately
            loader.classList.add('d-none');
            output.classList.remove('d-none');

            // Populate Executive Summary
            executiveTitle.textContent = data.executive_summary.title;
            displayText(executiveIntro, data.executive_summary.introduction);
            
            data.executive_summary.key_highlights.forEach(highlight => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = highlight;
                keyHighlights.appendChild(li);
            });

            // Populate Market Research
            displayText(industryAnalysis, data.market_research.industry_analysis);
            displayText(seoPotential, data.market_research.seo_potential);
            displayText(competitiveLandscape, data.market_research.competitive_landscape);

            // Populate Trademark Research
            displayText(trademarkConflicts, data.trademark_research.conflict_analysis);
            displayText(brandSafety, data.trademark_research.brand_safety);

            // Populate Keyword Research
            displayText(searchVolume, data.keyword_research.search_volume);
            displayText(cpcAnalysis, data.keyword_research.cpc_analysis);
            
            data.keyword_research.opportunity_keywords.forEach(keyword => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = keyword;
                opportunityKeywords.appendChild(li);
            });

            // Populate Competitor Research
            displayText(competitorAnalysis, data.competitor_research.analysis);
            
            // Populate competitor table
            data.competitor_research.competitors.forEach(competitor => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${competitor.name}</td>
                    <td>${competitor.domain}</td>
                    <td>${competitor.price}</td>
                    <td>${competitor.strategy}</td>
                `;
                competitorTable.appendChild(row);
            });

            // Populate Valuation section
            titleEl.textContent = data.valuation.title;
            expectedPrice.textContent = data.valuation.expected_price;
            displayText(aboutText, data.valuation.about);

            // Industries
            data.valuation.top_uses.forEach(item => {
                topIndustries.innerHTML += `
                    <li class="list-group-item">
                        <strong>${item.industry}</strong><br>
                        <small>${item.use}</small>
                    </li>
                `;
            });

            data.valuation.industries.forEach(industry => {
                extendedIndustries.innerHTML += `
                    <li class="list-group-item">${industry}</li>
                `;
            });

            displayText(finalSummaryText, data.final_summary);
        } catch (err) {
            console.error('API Error:', err);
            
            // Hide loader and show error
            loader.classList.add('d-none');
            
            // Show detailed error message to user
            let errorMessage = 'Something went wrong. Please try again.';
            
            if (err.message && err.message.includes('fetch')) {
                errorMessage = 'Network error: Please check your internet connection and try again.';
            } else if (err.message) {
                errorMessage = 'Error: ' + err.message;
            }
            
            alert(errorMessage);
            generateBtn.disabled = false;
            generateBtn.innerHTML = '<i class="fas fa-file-contract"></i> Generate Professional Report';
            
            // Hide result section if there was an error
            result.classList.add('d-none');
        }
        
    });
    
    // PDF Download functionality
    document.getElementById('downloadPdfBtn').addEventListener('click', function() {
        if (output.classList.contains('d-none')) {
            alert('Please generate a report first before downloading PDF.');
            return;
        }
        
        // Get the report content (the output section)
        const reportContent = document.querySelector('#output').cloneNode(true);
        
        // Remove loader if present
        const loaderInClone = reportContent.querySelector('#loader');
        if (loaderInClone) {
            loaderInClone.remove();
        }
        
        // Create a temporary container for the PDF content
        const tempContainer = document.createElement('div');
        tempContainer.style.cssText = 'position: absolute; left: -9999px; top: -9999px; width: 210mm; padding: 15mm; font-family: Arial, sans-serif; background: white; color: black;';
        
        // Add a header with the report title
        const header = document.createElement('div');
        header.innerHTML = '<h1 style="text-align: center; margin-bottom: 10px; font-size: 24px; color: #007bff;">Domain Evaluation Report</h1>';
        const domainName = document.getElementById('domain').value || 'domain-report';
        header.innerHTML += `<p style="text-align: center; margin-bottom: 20px; font-size: 16px;">Domain: ${domainName}</p>`;
        
        // Add table of contents
        const toc = document.createElement('div');
        toc.style.cssText = 'margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;';
        toc.innerHTML = '<h3 style="margin-top: 0;">Table of Contents</h3><ul style="margin-bottom: 0;">' +
            '<li><a href="#executive-summary" style="text-decoration: none; color: #007bff;">Executive Summary</a></li>' +
            '<li><a href="#market-research" style="text-decoration: none; color: #007bff;">Market Research & Analysis</a></li>' +
            '<li><a href="#trademark-research" style="text-decoration: none; color: #007bff;">Trademark Research</a></li>' +
            '<li><a href="#keyword-research" style="text-decoration: none; color: #007bff;">Keyword Search Volume Research</a></li>' +
            '<li><a href="#competitor-research" style="text-decoration: none; color: #007bff;">Competitor Research</a></li>' +
            '<li><a href="#valuation" style="text-decoration: none; color: #007bff;">Valuation & Commercial Assessment</a></li>' +
            '<li><a href="#final-summary" style="text-decoration: none; color: #007bff;">Final Summary</a></li>' +
            '</ul>';
        
        tempContainer.appendChild(header);
        tempContainer.appendChild(toc);
        tempContainer.appendChild(reportContent);
        
        document.body.appendChild(tempContainer);
        
        // Use html2canvas to capture the content
        html2canvas(tempContainer, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            scrollX: 0,
            scrollY: 0,
            logging: false
        }).then(canvas => {
            // Remove the temporary container
            document.body.removeChild(tempContainer);
            
            // Create PDF
            const {jsPDF} = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgData = canvas.toDataURL('image/jpeg', 0.9);
            
            // Calculate dimensions to fit A4
            const imgWidth = 210 - 20; // A4 width minus margins
            const pageHeight = 297 - 20; // A4 height minus margins
            const imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;
            let position = 10;
            
            // Add first page
            pdf.addImage(imgData, 'JPEG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
            
            // Add additional pages if needed
            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }
            
            // Save the PDF
            pdf.save(domainName + '-evaluation-report.pdf');
        }).catch(error => {
            console.error('Error generating PDF:', error);
            alert('Error generating PDF. Please try again.');
        });
    });
    </script>
    @endsection
