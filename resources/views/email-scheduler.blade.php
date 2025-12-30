@extends('layouts.app')

@section('title', 'Email Scheduler')

@section('content')
<style>
    .email-item {
        position: relative;
    }
    .delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        opacity: 0.7;
    }
    .delete-btn:hover {
        opacity: 1;
    }
</style>
<div class="container-fluid vh-100" style="margin: 0; padding: 0;">
    <div class="header text-center mb-4">
        <h2 class="mb-3">Email Scheduler (Indian Standard Time)</h2>
        
  
        
        <div class="current-time mb-3">
            Current IST Time: <span class="time-display fw-bold text-danger" id="current-time">{{ \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s A') }}</span>
        </div>
    </div>

    <div class="row h-100">
            <!-- Left Panel: Email Scheduling Form -->
            <div class="col-md-4 h-100">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">📧 Schedule New Email</h3>
                    </div>
                    <div class="card-body flex-grow-1 overflow-auto">
                        <form method="POST" action="/schedule-email" id="schedule-form">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="recipient_email" class="form-label">Recipient Email</label>
                                <input type="email" id="recipient_email" name="recipient_email" class="form-control" placeholder="Enter recipient email" required>
                            </div>


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
                            
                            <button type="submit" class="btn btn-success w-100">Schedule Email</button>
                        </form>
                        
                        <div id="form-messages" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <!-- Center Panel: Queued Emails -->
            <div class="col-md-4 h-100">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">⏳ Queued Emails</h3>
                    </div>
                    <div class="card-body flex-grow-1 overflow-auto">
                        <div id="queued-emails">
                            <p>Loading queued emails...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Sent Emails -->
            <div class="col-md-4 h-100">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">✅ Sent Messages</h3>
                    </div>
                    <div class="card-body flex-grow-1 overflow-auto">
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
                '<button class="btn btn-sm btn-danger delete-btn" onclick="deleteEmail(' + email.id + ')">🗑️</button>' +
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
                '<button class="btn btn-sm btn-danger delete-btn" onclick="deleteEmail(' + email.id + ')">🗑️</button>' +
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

    // Function to delete an email
    function deleteEmail(id) {
        if (confirm('Are you sure you want to delete this email?')) {
            fetch('/api/emails/' + id, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadEmails(); // Refresh the lists
                } else {
                    alert('Error deleting email: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error deleting email');
                console.error('Error:', error);
            });
        }
    }
    
    // Handle form submission
    document.getElementById('schedule-form').addEventListener('submit', function(e) {
        e.preventDefault();

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
                // Reset form
                document.getElementById('recipient_email').value = '';
                document.getElementById('subject').value = '';
                document.getElementById('body').value = '';
                document.getElementById('scheduled_at').value = '';

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
