@extends('layouts.app')

@section('title', 'Email Scheduler')

@section('content')
<div class="container mt-4">
    <div class="header text-center mb-4">
        <h2 class="mb-3">Email Scheduler (Indian Standard Time)</h2>
        
        <div class="timezone-notice bg-info text-white p-2 rounded mb-3">
            <strong>Note:</strong> All times are in Indian Standard Time (IST)
        </div>
        
        <div class="current-time mb-3">
            Current IST Time: <span class="time-display fw-bold text-danger" id="current-time">{{ \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s A') }}</span>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Left Panel: Email Scheduling Form -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">📧 Schedule New Email</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/schedule-email" id="schedule-form">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter email subject" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="body" class="form-label">Email Body</label>
                                <textarea id="body" name="body" class="form-control" placeholder="Enter email content" required rows="5"></textarea>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="scheduled_at" class="form-label">Schedule Time (IST)</label>
                                <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="form-control" required min="{{ \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d\TH:i') }}">
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Recipients</label>
                                <div id="recipients-container">
                                    <div class="recipient-input d-flex mb-2">
                                        <input type="email" id="new-recipient" class="form-control" placeholder="Enter recipient email" required>
                                        <button type="button" class="btn btn-outline-primary ms-2" onclick="addRecipientToList()">Add Mail</button>
                                    </div>
                                </div>
                                
                                <!-- Temporary list of added recipients -->
                                <div id="recipients-list" style="display: none;">
                                    <h4 class="mt-3">Added Recipients:</h4>
                                    <ul id="added-recipients-list" class="recipients-list list-group"></ul>
                                </div>
                                
                                <!-- Hidden input to store all recipient emails for form submission -->
                                <input type="hidden" name="recipients" id="recipients-hidden" value="">
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">Schedule Email</button>
                        </form>
                        
                        <div id="form-messages" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <!-- Center Panel: Queued Emails -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">⏳ Queued Emails</h3>
                    </div>
                    <div class="card-body">
                        <div id="queued-emails">
                            <p>Loading queued emails...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Panel: Sent Emails -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">✅ Sent Messages</h3>
                    </div>
                    <div class="card-body">
                        <div id="sent-emails">
                            <p>Loading sent messages...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Helper function to escape HTML
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return text.toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#x27;');
    }
    
    // Function to add recipient to the temporary list
    function addRecipientToList() {
        const emailInput = document.getElementById('new-recipient');
        const email = emailInput.value.trim();
        
        // Validate email format
        if (!email) {
            alert('Please enter an email address');
            return;
        }
                
        const emailRegex = /^[\w\.-]+@[\w\.-]+\.[\w\.-]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address');
            return;
        }
        
        // Check if email already exists in the list
        const existingRecipients = getRecipientsList();
        if (existingRecipients.includes(email)) {
            alert('This email is already in the list');
            return;
        }
        
        // Add to the temporary list
        const listContainer = document.getElementById('added-recipients-list');
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        listItem.innerHTML = '<span>' + email + '</span>' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="removeRecipientFromList(this, \'' + email + '\')">Remove</button>';
        listContainer.appendChild(listItem);
        
        // Add to hidden input
        updateRecipientsHiddenInput(email);
        
        // Show the list container
        document.getElementById('recipients-list').style.display = 'block';
        
        // Clear the input
        emailInput.value = '';
        emailInput.focus();
    }
    
    // Function to get current recipients list
    function getRecipientsList() {
        const hiddenInput = document.getElementById('recipients-hidden');
        return hiddenInput.value ? hiddenInput.value.split(',') : [];
    }
    
    // Function to update the hidden input with all recipients
    function updateRecipientsHiddenInput(newEmail) {
        const hiddenInput = document.getElementById('recipients-hidden');
        let recipients = hiddenInput.value ? hiddenInput.value.split(',') : [];
        
        if (newEmail && !recipients.includes(newEmail)) {
            recipients.push(newEmail);
        }
        
        hiddenInput.value = recipients.join(',');
    }
    
    // Function to remove recipient from the list
    function removeRecipientFromList(button, email) {
        const listItem = button.parentElement;
        listItem.remove();
        
        // Remove from hidden input
        const hiddenInput = document.getElementById('recipients-hidden');
        let recipients = hiddenInput.value ? hiddenInput.value.split(',') : [];
        recipients = recipients.filter(recipient => recipient !== email);
        hiddenInput.value = recipients.join(',');
        
        // Hide the list if empty
        if (recipients.length === 0) {
            document.getElementById('recipients-list').style.display = 'none';
        }
    }
    
    // Update current time every second
    function updateCurrentTime() {
        const now = new Date();
        // Format to IST manually
        const options = { 
            year: 'numeric', 
            month: '2-digit', 
            day: '2-digit', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: true,
            timeZone: 'Asia/Kolkata'
        };
        const istTime = now.toLocaleString('en-IN', options)
            .replace(/\//g, '-')
            .replace(/,\s/, ' ');
        document.getElementById('current-time').textContent = escapeHtml(istTime);
    }
    
    // Update time immediately and then every second
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
    
    // Load queued and sent emails
    function loadEmails() {
        fetch('/api/emails')
            .then(response => response.json())
            .then(data => {
                displayQueuedEmails(data.queued);
                displaySentEmails(data.sent);
            })
            .catch(error => console.error('Error loading emails:', error));
    }
    
    function displayQueuedEmails(emails) {
        const container = document.getElementById('queued-emails');
        
        if (emails.length === 0) {
            container.innerHTML = '<p class="text-muted">No queued emails.</p>';
            return;
        }
        
        let html = '';
        emails.forEach(email => {
            const overdueClass = email.is_overdue ? ' border-start border-danger border-4' : '';
            html += '<div class="email-item p-2 mb-2 border rounded' + overdueClass + '">' +
                '<div class="email-recipient fw-bold">' + escapeHtml(email.recipient_email) + '</div>' +
                '<div class="email-subject small">' + escapeHtml(email.subject) + '</div>' +
                '<div class="email-time small text-muted">' + escapeHtml(email.scheduled_at_ist) + '</div>' +
                '<div class="email-status status-' + escapeHtml(email.status) + ' small">Status: ' + escapeHtml(email.status) + '</div>' +
                (email.is_overdue ? '<div class="email-overdue text-danger fw-bold">⚠️ OVERDUE</div>' : '') +
                '</div>';
        });
        
        container.innerHTML = html;
    }
    
    function displaySentEmails(emails) {
        const container = document.getElementById('sent-emails');
        
        if (emails.length === 0) {
            container.innerHTML = '<p class="text-muted">No sent emails.</p>';
            return;
        }
        
        let html = '';
        emails.forEach(email => {
            html += '<div class="email-item p-2 mb-2 border rounded">' +
                '<div class="email-recipient fw-bold">' + escapeHtml(email.recipient_email) + '</div>' +
                '<div class="email-subject small">' + escapeHtml(email.subject) + '</div>' +
                '<div class="email-time small text-muted">' + escapeHtml(email.scheduled_at_ist) + '</div>' +
                '<div class="email-status status-' + escapeHtml(email.status) + ' small">Status: ' + escapeHtml(email.status) + '</div>' +
                '</div>';
        });
        
        container.innerHTML = html;
    }
    
    // Load emails immediately and then every 10 seconds
    loadEmails();
    setInterval(loadEmails, 10000);
    
    // Handle form submission
    document.getElementById('schedule-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate that at least one recipient is added
        const recipientsHidden = document.getElementById('recipients-hidden').value;
        const recipients = recipientsHidden ? recipientsHidden.split(',') : [];
        
        if (recipients.length === 0) {
            document.getElementById('form-messages').innerHTML = '<div class="alert alert-danger">Please add at least one recipient email.</div>';
            return;
        }
        
        let allValid = true;
        const emailRegex = /^[\w\.-]+@[\w\.-]+\.[\w\.-]+$/;
                
        recipients.forEach(email => {
            if (!emailRegex.test(email.trim())) {
                allValid = false;
            }
        });
        
        if (!allValid) {
            document.getElementById('form-messages').innerHTML = '<div class="alert alert-danger">Please enter valid email addresses for all recipients.</div>';
            return;
        }
        
        const formData = new FormData(this);
        const messagesDiv = document.getElementById('form-messages');
        
        fetch('/schedule-email', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messagesDiv.innerHTML = '<div class="alert alert-success">' + escapeHtml(data.success) + '</div>';
                // Reset form completely
                document.getElementById('subject').value = '';
                document.getElementById('body').value = '';
                document.getElementById('scheduled_at').value = '';
                
                // Clear the temporary recipients list
                document.getElementById('added-recipients-list').innerHTML = '';
                document.getElementById('recipients-hidden').value = '';
                document.getElementById('recipients-list').style.display = 'none';
                
                loadEmails(); // Refresh the email lists
            } else {
                messagesDiv.innerHTML = '<div class="alert alert-danger">' + escapeHtml(data.error || 'An error occurred') + '</div>';
            }
        })
        .catch(error => {
            messagesDiv.innerHTML = '<div class="alert alert-danger">An error occurred while scheduling the email.</div>';
            console.error('Error:', error);
        });
    });
    
    // Update form's min datetime when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        // Add 1 minute to current time
        now.setMinutes(now.getMinutes() + 1);
        
        // Format to datetime-local input format (YYYY-MM-DDTHH:mm)
        const formatted = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0') + 'T' +
            String(now.getHours()).padStart(2, '0') + ':' +
            String(now.getMinutes()).padStart(2, '0');
            
        document.getElementById('scheduled_at').min = formatted;
    });
</script>
@endsection
