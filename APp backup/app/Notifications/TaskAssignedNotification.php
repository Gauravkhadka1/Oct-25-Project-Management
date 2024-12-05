<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;
use App\Models\ClientTask;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Constructor accepts any of the Task types
     *
     * @param Task|PaymentTask|ProspectTask|ClientTask $task
     */
    public function __construct($task)
    {
        if (!($task instanceof Task || $task instanceof PaymentTask || $task instanceof ProspectTask|| $task instanceof ClientTask)) {
            throw new \InvalidArgumentException("Invalid task type");
        }

        $this->task = $task;
    }

    /**
     * Notification delivery channels
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Format the database notification data
     */
    public function toDatabase($notifiable)
    {
        $taskType = strtolower(class_basename($this->task));
        $assignedBy = $this->task->assignedBy->username ?? 'System';
        $relatedName = $this->getRelatedName();

        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'task_type' => $taskType,
            'assigned_by' => $assignedBy,
            'related_name' => $relatedName,
            'message' => $this->formatMessage($taskType, $assignedBy, $relatedName),
        ];
    }

    /**
     * Format the email notification content
     */
    public function toMail($notifiable)
    {
        $taskType = strtolower(class_basename($this->task));
        $assignedBy = $this->task->assignedBy->username ?? 'System';
        $relatedName = $this->getRelatedName();

        return (new MailMessage)
            ->subject('New Task Assigned')
            ->line($this->formatMessage($taskType, $assignedBy, $relatedName))
            // ->action('View Task', url('/dashboard/'))
            ->line('Please check the task and complete it by the due date.');
    }

    /**
     * Generate the formatted message
     *
     * @param string $taskType
     * @param string $assignedBy
     * @param string $relatedName
     * @return string
     */
    protected function formatMessage($taskType, $assignedBy, $relatedName)
    {
        return "{$assignedBy} has assigned you a new task '{$this->task->name}' in {$relatedName}.";
    }

    /**
     * Get the related name based on the task type
     *
     * @return string
     */
    protected function getRelatedName()
    {
        if ($this->task instanceof Task) {
            return $this->task->project->name ?? 'No project'; // Assuming Task has a `project` relationship
        }

        if ($this->task instanceof PaymentTask) {
            return $this->task->payment->company_name ?? 'No payment company'; // Assuming PaymentTask has a `payment` relationship
        }

        if ($this->task instanceof ProspectTask) {
            return $this->task->prospect->company_name ?? 'No prospect company'; // Assuming ProspectTask has a `prospect` relationship
        }
        if ($this->task instanceof ClientTask) {
            return $this->task->client->company_name ?? 'No client company'; // Assuming ProspectTask has a `prospect` relationship
        }

        return 'Unknown';
    }
}
